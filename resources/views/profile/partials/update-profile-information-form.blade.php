<section>
    <header class="mb-6">
        <h2 class="text-base font-bold text-slate-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="text-xs text-slate-500 mt-1">
            {{ __("Perbarui data nama, username, email, dan foto profil Anda.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 pb-6 border-b border-slate-100">
            <div class="shrink-0 text-center">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-2xl object-cover border-2 border-indigo-100 shadow-sm mx-auto mb-2">
                @else
                    <div class="w-24 h-24 rounded-2xl bg-gradient-to-tr from-indigo-600 to-cyan-500 text-white font-bold text-3xl flex items-center justify-center mx-auto mb-2 shadow-md shadow-indigo-500/20">
                        {{ strtoupper(substr($user->fullname, 0, 1)) }}
                    </div>
                @endif
                <label for="avatar" class="cursor-pointer inline-block text-xs font-bold text-indigo-600 hover:text-indigo-700">
                    Ganti Foto
                </label>
                <input id="avatar" name="avatar" type="file" accept="image/*" class="hidden">
                @error('avatar')
                    <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="fullname" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Nama Lengkap') }}</label>
                    <input id="fullname" name="fullname" type="text" value="{{ old('fullname', $user->fullname) }}" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                    @error('fullname')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Username / NIS') }}</label>
                    <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required class="w-full px-3.5 py-2.5 text-xs font-mono bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                    @error('username')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Alamat Email') }}</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                    @error('email')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">{{ __('Nomor Telepon / WhatsApp') }}</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="081234567890" class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-colors">
                    @error('phone')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm shadow-indigo-500/20 cursor-pointer">
                {{ __('Simpan Perubahan') }}
            </button>
        </div>
    </form>
</section>
