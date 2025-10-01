 @php
     $favicon = App::make('settingItems')['favicon']->value ?? null;
     $faviconUrl = $favicon ? Storage::url($favicon) : asset('assets/images/favicon.png');
 @endphp

 <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center space-x-1 p-2 rounded-lg animated-gradient transition">
     <img src="{{ $faviconUrl }}" alt="Logo" class="h-8 w-8 rounded object-contain">
     <span class="text-base font-bold text-gray-600 leading-none">
         {{ App::make('settingItems')['site_name']->value ?? 'Site Name' }}
     </span>
 </a>

 <style>
     header.fi-sidebar-header div .fi-logo {
         height: 100% !important;
         padding-top: 0.5rem;
         padding-bottom: 0.5rem;
     }
 </style>

 <style>
     .fi-logo .animated-gradient {
         background: linear-gradient(-45deg,
                 #c7d2fe,
                 #d8b4fe,
                 #fbcfe8,
                 #fde68a,
                 #bbf7d0,
                 #bae6fd);
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
 </style>
