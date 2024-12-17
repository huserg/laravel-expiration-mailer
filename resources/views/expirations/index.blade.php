<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Manage expirations') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 text-white min-h-screen flex flex-col lg:flex-row items-center lg:items-start justify-center p-4 space-y-6 lg:space-x-6 lg:space-y-0">
<!-- Container pour la Liste -->
<div class="w-full max-w-7xl bg-gray-900 rounded-lg shadow-md p-6">
    <!-- Title -->
    <h2 class="text-2xl font-bold text-gray-100 mb-4 text-center">
        {{ __('Expiration List') }}
    </h2>

    @if ($expirations->isNotEmpty())
        <!-- Responsive Table -->
        <div class="hidden md:block">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-md mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-4 rounded-md mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <table class="w-full table-auto border-collapse border border-gray-600">
                <thead>
                <tr class="bg-gray-800">
                    <th class="border border-gray-600 px-4 py-2">{{ __('Name') }}</th>
                    <th class="border border-gray-600 px-4 py-2">{{ __('Expiration Date') }}</th>
                    <th class="border border-gray-600 px-4 py-2">{{ __('Emails') }}</th>
                    <th class="border border-gray-600 px-4 py-2">{{ __('Message') }}</th>
                    <th class="border border-gray-600 px-4 py-2">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($expirations as $expiration)
                    <tr class="hover:bg-gray-800">
                        <td class="border border-gray-600 px-4 py-2">{{ $expiration->name }}</td>
                        <td class="border border-gray-600 px-4 py-2">{{ $expiration->expiration_date }}</td>
                        <td class="border border-gray-600 px-4 py-2">
                            {{ implode(', ', json_decode($expiration->emails, true) ?? []) }}
                        </td>
                        <td class="border border-gray-600 px-4 py-2">{{ $expiration->message }}</td>
                        <td class="border border-gray-600 px-4 py-2 text-center">
                            <form method="POST" action="{{ route('lem.expirations.force-send', $expiration->id) }}">
                                @csrf
                                <button type="submit"
                                        class="bg-blue-500 text-white font-semibold py-1 px-2 rounded-lg hover:bg-blue-600 transition duration-300">
                                    {{ __('Send Email') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile View (Cards) -->
        <div class="md:hidden space-y-4">
            @foreach ($expirations as $expiration)
                <div class="bg-gray-800 border border-gray-600 rounded-lg p-4 shadow-sm flex">
                    <div class="">
                        <p><strong>{{ __('Name') }}:</strong> {{ $expiration->name }}</p>
                        <p><strong>{{ __('Expiration Date') }}:</strong> {{ $expiration->expiration_date }}</p>
                        <p><strong>{{ __('Emails') }}:</strong>
                            {{ implode(', ', json_decode($expiration->emails, true) ?? []) }}
                        </p>
                        <p><strong>{{ __('Message') }}:</strong> {{ $expiration->message }}</p>
                    </div>
                    <div>
                        <form method="POST" action="{{ route('lem.expirations.force-send', $expiration->id) }}">
                            @csrf
                            <button type="submit"
                                    class="bg-blue-500 text-white font-semibold py-1 px-2 rounded-lg hover:bg-blue-600 transition duration-300">
                                {{ __('Send Email') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600 text-center">{{ __('No expirations available.') }}</p>
    @endif
</div>

<!-- Form Container -->
<div class="w-full lg:max-w-md bg-gray-900 rounded-lg shadow-md p-6">
    <!-- Title -->
    <h1 class="text-2xl font-bold text-gray-100 text-center mb-4">
        {{ __('Add an expiration') }}
    </h1>

    <!-- Form -->
    <form method="POST" action="{{ route('lem.expirations.store') }}" class="space-y-4 text-black">
        @csrf
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-200">{{ __('Name') }}</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500
               @error('name') border-red-500 @enderror"
                   placeholder="{{ __('Enter a name') }}" required>
            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Expiration Date -->
        <div>
            <label for="expiration_date" class="block text-sm font-medium text-gray-200">{{ __('Expiration date') }}</label>
            <input type="date" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}"
                   class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500
               @error('expiration_date') border-red-500 @enderror" required>
            @error('expiration_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Emails -->
        <div>
            <label for="emails" class="block text-sm font-medium text-gray-200">{{ __('Emails') }}</label>
            <input type="text" id="emails" name="emails" value="{{ old('emails') }}"
                   class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500
           @error('emails') border-red-500 @enderror"
                   placeholder="email@example.com, email2@example.com" required>
            @error('emails') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Message -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-200">{{ __('Message') }}</label>
            <textarea id="message" name="message" rows="4"
                      class="w-full px-3 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500
                  @error('message') border-red-500 @enderror"
                      placeholder="{{ __('Enter your custom message here...') }}">{{ old('message') }}</textarea>
            @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit"
                    class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300">
                {{ __('Add') }}
            </button>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="text-green-500 text-center mt-4">
                {{ session('success') }}
            </div>
        @endif
    </form>
</div>
</body>
</html>
