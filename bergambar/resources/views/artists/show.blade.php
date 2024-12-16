@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 1.5%;">
        <h2>{{ $artist->name }}'s Portfolio</h2>
        <p>Email: {{ $artist->email }}</p>

        <h3><u>Commissions I've Made</u></h3>
        @foreach($artist->commissions as $commission)
            <div>
                <h4>{{ $commission->description }}</h4>
                <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image" class="img-fluid" style="width: 250px; height:auto;">
                <h5>Reviews:</h5>
                @foreach($commission->reviews as $review)
                    <p>{{ $review->review }} - by <u>{{ $review->user->name }}</u></p>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
