 <x-filament-panels::page.simple heading=''>
     <style>
         .fi-simple-header {
             display: none;
         }

         .fi-simple-layout {
             background: linear-gradient(-45deg,
                     #c7d2fe,
                     /* biru muda pastel */
                     #d8b4fe,
                     /* ungu muda pastel */
                     #fbcfe8,
                     /* pink muda pastel */
                     #fde68a,
                     /* kuning pastel */
                     #bbf7d0,
                     /* hijau pastel */
                     #bae6fd
                     /* biru laut pastel */
                 );
             background-size: 400% 400%;
             animation: gradientShift 8s ease infinite;
         }

         @keyframes gradientShift {
             0% {
                 background-position: 0% 50%;
             }

             50% {
                 background-position: 100% 50%;
             }

             100% {
                 background-position: 0% 50%;
             }
         }

         .fi-simple-main-ctn {
             padding: 2rem;
         }

         .fi-simple-main {
             border-radius: 1rem;
         }
     </style>

     <div class="text-center">
         <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="mx-auto h-20 rounded">

         <div class="mt-2">
             <div class="text-2xl font-bold text-primary-600">
                 {{ $settingItems['site_name']->value ?? 'Site Name' }}
             </div>
         </div>

         <p class="mt-4 text-gray-500">
             Please log in using your registered email and password.
         </p>
     </div>

     <form wire:submit.prevent="authenticate" class="mt-6">
         {{ $this->form }}

         <div class="mt-4 space-y-4">
             <x-filament::button type="submit" color="primary" wire:target="authenticate" wire:loading.attr="disabled" spinner class="w-full">
                 Login
             </x-filament::button>


             @include('auth.google-sign-in-button')
         </div>
     </form>
 </x-filament-panels::page.simple>
