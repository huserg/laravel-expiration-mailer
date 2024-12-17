<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ __('Manage expirations') }}</title>
</head>
<body>
<h1>{{ __('Add an expiration') }}</h1>
<form method="POST" action="{{ route('expirations.store') }}">
    @csrf
    <label>{{ __('Name') }} :</label>
    <input type="text" name="name" required><br>

    <label>{{ __('Expiration date') }} :</label>
    <input type="date" name="expiration_date" required><br>

    <label>{{ __('Emails, separated by commas') }} :</label>
    <input type="text" name="emails" required><br>

    <label>{{ __('Message') }} :</label>
    <textarea name="message"></textarea><br>

    <button type="submit">{{ __('Add') }}</button>
</form>
</body>
</html>
