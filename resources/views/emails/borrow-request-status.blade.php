<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Borrow Request Status</title>
</head>
<body>
    <h2>Hello {{ $borrowRequest->user->name }},</h2>

    <p>Your request to borrow the book <strong>"{{ $borrowRequest->book->title }}"</strong> has been <strong>{{ ucfirst($borrowRequest->status) }}</strong>.</p>

    @if($borrowRequest->status === 'approved')
        <p>✅ Please visit the library to pick up your book.</p>
    @elseif($borrowRequest->status === 'rejected')
        <p>❌ Unfortunately, your request was not approved.</p>
    @endif

    <p><strong>Requested on:</strong> {{ \Carbon\Carbon::parse($borrowRequest->request_date)->toDayDateTimeString() }}</p>

    <br>
    <p>Thank you,<br>Library Management System</p>
</body>
</html>
