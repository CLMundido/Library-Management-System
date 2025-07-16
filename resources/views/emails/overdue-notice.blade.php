<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Overdue Book Notice</title>
</head>
<body>
    <h2>Hello {{ $record->user->name }},</h2>

    <p>This is a reminder that the book <strong>"{{ $record->book->title }}"</strong> you borrowed is now <strong>overdue</strong>.</p>

    <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($record->due_date)->toFormattedDateString() }}</p>

    <p>Please return the book as soon as possible to avoid further penalties.</p>

    <p>Thank you,<br>Library Management System</p>
</body>
</html>
