<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\ExtraService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Providers\PricingProvider;

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
                ->get();
        } else {
            $reservations = Reservation::with('extraServices')
                ->where('client_id', Auth::user()->client->id)
                ->get();
        }

        return view('reservations', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

            $reservation->save();

            return response()->json([
                'status' => 201,
                'message' => 'OK',
                'reservation' => $reservation
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 400,
                'message' => $th
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
