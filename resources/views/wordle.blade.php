<x-guest-layout>
    <x-slot name="title">
        Wordle Guess Check
    </x-slot>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img style="display:block;margin:auto;width: 50%;height: 100%;object-fit: contain;" src="{{ asset('img/check_logo.png') }}" />
            </a>
        </x-slot>

        <div class="card-body">
            <!-- Session Status -->
            <x-auth-session-status class="mb-3" :status="session('status')" :success="session('success')" :warning="session('warning')" :danger="session('danger')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-3" :errors="$errors" />

            <form method="POST" action="{{ route('wordle.guess') }}">
                @csrf

                <!-- Wordle Guess -->
                <div class="mb-3">
                    <x-label for="guess" value="Check Wordle guess" />

                    <x-input id="guess" type="text" name="guess" required autofocus />
                </div>
                
                <!-- Time Zone -->
                <input name="timezone" type="hidden" id="timezone" value="">

                <!-- Detailed feedback -->
                <div class="mb-3">
                    <div class="form-check">
                        <x-checkbox id="details" name="details" :checked="old('details')" />
                        <label class="form-check-label" for="details">Detailed response?</label>
                    </div>
                </div>

                <div class="mb-0">
                        <x-button class="float-end">SUBMIT</x-button>
                    </div>
                </div>
            </form>
        </div>
        <script>
            window.onload = function(event) {
                document.getElementById('timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;

                function refreshToken(){
                    var xhr = new XMLHttpRequest();

                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            document.getElementsByName('_token').forEach(function(element) {
                                element.value = xhr.response;
                            });
                        }
                    };

                    xhr.open('GET', '{{ route('refresh-csrf') }}');
                    xhr.send();
                }

                setInterval(refreshToken, 3600000); // 1 hour
            };
        </script>
    </x-auth-card>
</x-guest-layout>
