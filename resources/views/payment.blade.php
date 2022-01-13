@extends('layouts.app')

@section('content')
<div class="container section__content">
  <div class="row justify-content-center">
      <div class="col-6">
        <div class="card border-0 shadow p-4">
          <h2 class="text-center mb-4">Payment</h2>
          <h3 class="text-secondary text-center">{{ number_format($total, 2) }} €</h3>
          <div class="alert alert-success d-none" id="payment-success-alert">
            Le paiment a été bien effectué. Merci à vous.
          </div>
          <div class="alert alert-danger d-none" id="payment-danger-alert">
            Il y a eu un problem avec votre carte bancaire et le payment n'a pas été éffectué.
          </div>
          <input class="form-control" id="card-holder-name" placeholder="Nom du propiétaire de la carte bancaire" type="text">
  
          <!-- Stripe Elements Placeholder -->
          <div id="card-element" class="p-3"></div>

          <div id="errors-container"></div>
          <button class="btn btn-primary" id="card-button">
            Payer
          </button>

          <div class="text-center d-none" id="payment-spinner-loader">
            <i class="fas fa-spinner fa-spin mt-3 h1 text-secondary"></i>
          </div>
        </div>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>

<script>
  const reservationStatus = {!! json_encode($reservation->status) !!};
  if (reservationStatus === window.constants.RESERVATION_PAID) {
    window.location.href = '/reservations';
  }

  const pendingQuote = JSON.parse(localStorage.getItem('pendingQuote'));
  const quoteFormData = JSON.parse(localStorage.getItem('quoteFormData'));
  const stripe = Stripe('pk_test_51J95ZWKTCTuDB0K7Myc1hYtpgQToXIOAFGLSEgEumVmY1TT9Ij2NUQf93gKMXDDEWEwZ1o77BhFNGYOszQPCXGSr00i1GM8qGB');

  const elements = stripe.elements();
  const cardElement = elements.create('card');

  cardElement.mount('#card-element');

  // Create payment method
  const cardHolderName = document.getElementById('card-holder-name');
  const cardButton = document.getElementById('card-button');

  cardButton.addEventListener('click', async (e) => {
    window.$('#errors-container').empty();

    const { paymentMethod, error } = await stripe.createPaymentMethod(
      'card', cardElement, {
        billing_details: { name: cardHolderName.value }
      }
    );

    paymentMethod['amount'] = {!! json_encode(number_format($total, 2)) !!};
    paymentMethod['reservation_id'] = {!! json_encode($reservation->id) !!};

    if (error) {
      let { message } = error;
      if (error.code === 'parameter_invalid_empty') {
        message = 'Le nom du propriétaire est necessaire.'
      }

      window.$('#errors-container').append(`<p class="text-danger text-center">${message}</p>`);
    } else {
      window.$('#payment-spinner-loader').removeClass('d-none');
      window.$('#card-button').prop('disabled', true);
      window.axios.post('/purchase', paymentMethod)
        .then(response => {
          console.log(response);
          const {reservation} = response.data;
          window.$('#payment-success-alert').removeClass('d-none');
          window.$('#payment-danger-alert').addClass('d-none');
          window.location.href = '/reservations';
        })
        .catch(() => {
          window.$('#payment-danger-alert').removeClass('d-none');
        })
        .finally(() => {
          window.$('#payment-spinner-loader').addClass('d-none');
          window.$('#card-button').prop('disabled', false);
        });
    }
  });
</script>
@endpush