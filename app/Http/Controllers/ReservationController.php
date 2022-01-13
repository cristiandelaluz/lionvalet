<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\ExtraService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Providers\PricingProvider;
use App\Providers\ReservationStatusProvider;
use Cartalyst\Stripe\Stripe;

class ReservationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = null;
        $dateMin = Carbon::today()->subDays(7);
        $dateMax = Carbon::today()->addDays(7);

        if (Auth::user()->hasRole('admin')) {
            $reservations = Reservation::with('extraServices')
                ->with('client.user')
                ->where('departure_date','>=',$dateMin)
                ->where('departure_date','<=',$dateMax)
                ->orderByDesc('created_at')
                ->get();
        } else {
            $reservations = Reservation::with('extraServices')
                ->where('client_id', Auth::user()->client->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('reservations', compact('reservations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $reservation = new Reservation;

            $reservation->departure_place = $request->departure_place;
            $reservation->departure_meeting_point = $request->departure_meeting_point;
            $reservation->departure_date = $request->departure_date;
            $reservation->departure_hour = $request->departure_hour;
            $reservation->departure_ticket_number = $request->departure_ticket_number;
            $reservation->arrival_place = $request->arrival_place;
            $reservation->arrival_meeting_point = $request->arrival_meeting_point;
            $reservation->arrival_date = $request->arrival_date;
            $reservation->arrival_hour = $request->arrival_hour;
            $reservation->arrival_ticket_number = $request->arrival_ticket_number;
            $reservation->client_id = Auth::user()->client->id;
            $reservation->save();

            // Calculate total
            $departure = new Carbon($reservation->departure_date);
            $arrival = new Carbon($reservation->arrival_date);
            $daysNumber = $arrival->diff($departure)->days;

            if ($daysNumber <= 0) {
                $daysNumber = 1;
            }

            $reservation->total = PricingProvider::PARKING_PRICE * $daysNumber + PricingProvider::REGULAR_PRICE;

            // time
            $departureHourSplitted = explode(':', $reservation->departure_hour);
            $arrivalHourSplitted = explode(':', $reservation->arrival_hour);

            if (intval($departureHourSplitted[0]) >= 0 && intval($departureHourSplitted[0]) <= 6) {
                $reservation->total += PricingProvider::NIGTH_PRICE - PricingProvider::REGULAR_PRICE;

                if (intval($departureHourSplitted[0]) == 6 && intval($departureHourSplitted[1]) > 0) {
                    $reservation->total -= PricingProvider::NIGTH_PRICE;
                    $reservation->total += PricingProvider::REGULAR_PRICE;
                }
            }

            if (intval($arrivalHourSplitted[0]) >= 0 && intval($arrivalHourSplitted[0]) <= 6) {
                $reservation->total += PricingProvider::NIGTH_PRICE - PricingProvider::REGULAR_PRICE;

                if (intval($arrivalHourSplitted[0]) == 6 && intval($arrivalHourSplitted[1]) > 0) {
                    $reservation->total -= PricingProvider::NIGTH_PRICE;
                    $reservation->total += PricingProvider::REGULAR_PRICE;
                }
            }

            if (count($request->services) > 0) {
                $reservation->extraServices()->attach($request->services);
                $services_total = DB::table('extra_services')
                ->select(DB::raw('sum(price) as total'))
                ->whereIn('id', $request->services)
                ->get();

                $reservation->services_total = $services_total[0]->total;
            }

            // forwarding
            if (isset($request->is_forwarding)) {
                $reservation->total += PricingProvider::FORWARDING_PRICE;
                $reservation->is_forwarding = true;
            }

            $reservation->save();

            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'reservation' => $reservation
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e
            ]);
        }
    }

    /**
     * Payment with Stripe
     *
     * @return \Illuminate\Http\Response
     */
    public function payment($id)
    {
        $reservation = Reservation::with('extraServices')->find($id);
        $total = $reservation->total;

        if (count($reservation->extraServices)) {
            foreach ($reservation->extraServices as $extraService) {
                $total += $extraService->price;
            }
        }

        return view('payment', compact('reservation', 'total'));
    }

    /**
     * Complete the purchase and update the reservation
     * 
     * @return \Illuminate\Http\Response
     */
    public function purchase(Request $request) {
        try {
            $payment_method_id = $request->id;
            $amount = $request->amount;
            $reservation_id = $request->reservation_id;
            $user = $request->user();
            $payment = $user->charge($amount * 100, $payment_method_id, ['currency' => 'eur']);
            $reservation = Reservation::find($reservation_id);
            $reservation->payment_method_id = $payment->id;
            $reservation->status = ReservationStatusProvider::PAID;
            $reservation->save();

            return response()->json([
                'status' => 200,
                'message' => 'OK',
                'reservation' => $reservation
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e
            ]);
        }
    }

    public function refund(Request $request)
    {
        try {
            $reservationId = $request->id;
            $payment_method_id = $request->payment_method_id;
            $reservation = Reservation::with('extraServices')->find($reservationId );
            $total = $reservation->total;

            if (count($reservation->extraServices)) {
                foreach ($reservation->extraServices as $extraService) {
                    $total += $extraService->price;
                }
            }

            $stripe = new Stripe(env('STRIPE_SECRET'));
            $refund = $stripe->refunds()->create($payment_method_id, $total - 7, ['reason' => 'requested_by_customer']);
            $reservation->status = ReservationStatusProvider::REFUNDED;
            $reservation->save();

            return response()->json([
                'status' => 200,
                'message' => 'OK'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e
            ]);
        }
    }
}
