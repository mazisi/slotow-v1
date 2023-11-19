<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

      <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"

      />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/favicon/apple-touch-icon.png') }}">
<link rel="icon" type="{{ asset('public/favicon/image/png') }}" sizes="32x32" href="{{ asset('public/favicon/favicon-32x32.png') }}">
<link rel="icon" type="{{ asset('public/favicon/image/png') }}" sizes="16x16" href="{{ asset('public/favicon/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('public/favicon/site.webmanifest') }}">
      <!-- Material Icons -->

      <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>

      
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>



        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


        <script src="https://cdn.tiny.cloud/1/9l6lug40mtg10elrk1ncpkmwgmd9gmu8xggx0h2i8ad8xt82/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>



        <script src="{{ mix('js/app.js') }}" defer></script>

        @inertiaHead

        <style>
  .multiselect{
    margin:top: 1rem; border-color: #4caf50 !important;box-shadow: inset 1px 0 #4caf50
  }
            .half-circle-spinner, .half-circle-spinner * {

                  box-sizing: border-box;

                }

            

                .half-circle-spinner {

                  width: 20px;

                  height: 20px;

                  border-radius: 100%;

                  position: relative;

                }

            

                .half-circle-spinner .circle {

                  content: "";

                  position: absolute;

                  width: 100%;

                  height: 100%;

                  border-radius: 100%;

                  border: calc(60px / 10) solid transparent;

                }

            

                .half-circle-spinner .circle.circle-1 {

                  border-top-color: #fff;

                  animation: half-circle-spinner-animation 1s infinite;

                }

            

                .half-circle-spinner .circle.circle-2 {

                  border-bottom-color: #fff;

                  animation: half-circle-spinner-animation 1s infinite alternate;

                }

            

                @keyframes half-circle-spinner-animation {

                  0% {

                    transform: rotate(0deg);

            

                  }

                  100%{

                    transform: rotate(360deg);

                  }

                }



.navbar-vertical .navbar-brand > img, .navbar-vertical .navbar-brand-img {

    max-height: 8rem !important;
    width: 250px;

    height: 11rem;

    margin-top: -2.9rem;

    margin-left: -1.7rem;

    }

    .limit-file-name{
      width: 130px; 
      overflow: hidden; 
      height: 40px
    }

    .modal {
      z-index: 9999 !important;
    }

    .modal-backdrop {
      z-index: 9989 !important;
     }

     .centered-select {
    width: 200px; /* Adjust the width as needed */
    margin: 0 auto;
  }

   .centered-select option {
    text-align: center;
  }
    
   </style>

    </head>

    <body class="g-sidenav-show " >

        @routes

        @inertia

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ></script>

       <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    </body>

</html>
