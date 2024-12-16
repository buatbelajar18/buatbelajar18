@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="text-black fw-semibold">Artists and Reviews</h2>
    <div class="row">
        @if($artists->isEmpty())
            <p>No artists available at the moment.</p>
        @else
            @foreach($artists as $artist)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                    @foreach($artists as $artist)
                        <a href="{{ route('artists.search', $artist->id) }}">
                            <h5 class="card-title fw-bold fst-italic">{{ $artist->name }}</h5>
                            <p><strong class="fw-bold fst-italic">Email:</strong> {{ $artist->email }}</p>
                        </a>
                    @endforeach
                       

                        @foreach($artist->commissions as $commission)
                            <h6 class="mt-3 fw-bold fst-italic">Commission: {{ $commission->description }}</h6>
                            <!-- <p><strong>Status:</strong> {{ $commission->status }}</p>
                            <p><strong>Price:</strong> ${{ $commission->total_price }}</p> -->

                            @if($commission->reviews->isEmpty())
                                <p>No reviews yet.</p>
                            @else
                                <h6 class="fw-bold fst-italic">Reviews:</h6>
                                @foreach($commission->reviews as $review)
                                    <div class="review">
                                        <strong>{{ $review->user->name ?? 'Unknown' }}</strong>
                                        <p>{{ $review->review }}</p>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
