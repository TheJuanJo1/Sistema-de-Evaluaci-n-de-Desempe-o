<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('informacion de perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Actualizar la información del perfil y correo electrónico de su cuenta.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Correo')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            <!-- Signature upload -->
            <x-input-label for="signature" :value="__('Firma')" class="mt-4" />
            <x-text-input id="signature" name="signature" type="file" accept="image/*" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('signature')" />
            @if($user->signature)
                <div class="mt-2 flex items-center gap-4">
                    <p class="text-sm text-gray-600">{{ __('Firma actual:') }}</p>
                    <img src="{{ asset('storage/' . $user->signature) }}" alt="Firma" class="h-24" />
                </div>
@endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
<!-- Signature display and delete -->
@if($user->signature)
    <div class="mt-4 flex items-center gap-4">
        <p class="text-sm text-gray-600">{{ __('Firma actual:') }}</p>
        <img src="{{ asset('storage/' . $user->signature) }}" alt="Firma" class="h-24" />
        <form method="post" action="{{ route('profile.signature.destroy') }}" class="ml-2">
            @csrf
            @method('delete')
            <x-danger-button>{{ __('Eliminar firma') }}</x-danger-button>
        </form>
    </div>
@endif
</section>
