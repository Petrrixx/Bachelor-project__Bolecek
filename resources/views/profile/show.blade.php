@extends('layouts.base')

@section('title', 'Môj profil')

@section('content')
    <style>
        .profile-container {
            margin-top: 50px;
            padding: 20px;
            background-color: #2a2a2a;
            border-radius: 10px;
        }

        .profile-container h1 {
            margin-bottom: 30px;
            color: #fff;
        }

        .profile-details p {
            font-size: 1.1em;
            margin-bottom: 15px;
            color: #ddd;
        }

        .edit-profile-btn {
            margin-top: 20px;
        }
    </style>

    <div class="container">
        <div class="profile-container">
            <h1>Môj profil</h1>

            <div class="profile-details">
                <p><strong>Meno:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Telefón:</strong> {{ $user->contact ?? 'Nie je zadaný' }}</p>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn btn-primary edit-profile-btn">Upraviť profil</a>
        </div>
    </div>
@endsection

{{--@section('scripts')--}}
{{--    <script>--}}
{{--    </script>--}}
{{--@endsection--}}
