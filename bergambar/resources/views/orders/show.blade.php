@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="row">
            <!-- Column for Image -->
            <div class="col-md-6 text-center mb-4 mb-md-0">
                <img src="{{ asset('storage/' . $commission->image) }}" alt="Commission Image" class="img-fluid rounded" style="max-width: 100%; height: auto;">
            </div>

            <!-- Column for Details and Description -->
            <div class="col-md-6">
                <h2>{{ $commission->title }}</h2>
                <p><strong>Artist:</strong> {{ $artist->name ?? 'Unknown Artist' }}</p>
                <p><strong>Description:</strong> {{ $commission->description ?? 'No description available' }}</p>
                <p><strong>Price:</strong> Rp{{ number_format($commission->total_price, 0, ',', '.') }}</p>

                <!-- Order and Chat Buttons -->
                <div class="order-buttons mt-3">
                    <!-- Order Now Button triggers the modal -->
                    <a href="#" class="btn btn-primary me-2 mb-2" data-bs-toggle="modal" data-bs-target="#paymentModal">Order Now</a>
                    <!-- Contact Artist Button (direct link) -->
                    <a href="{{ route('chat.show', $artist->id) }}" class="btn btn-success mb-2">Contact Artist</a>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h4>Comments</h4>
                    @if($commission->reviews->isEmpty())
                        <p>No Comments yet.</p>
                    @else
                        @foreach($commission->reviews as $review)
                            <div class="review mb-4">
                                <strong>{{ $review->user->name ?? 'Unknown' }}</strong> <span class="text-warning">★</span>
                                <p>{{ $review->review }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Add Review Form -->
        @auth
            <form action="{{ route('commissions.addReview', $commission->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="review" class="form-label">Your Comment</label>
                    <textarea name="review" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary d-flex ms-auto me-0">Submit Comment</button>
            </form>
        @endauth
    </div>
</div>

<!-- Modal for Payment -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Commission Description -->
                <p><strong>Description:</strong> {{ $commission->description }}</p>

                <!-- Artist Name -->
                <p><strong>Artist:</strong> {{ $artist->name ?? 'Unknown Artist' }}</p>

                <!-- Buyer Name -->
                <p><strong>Buyer:</strong> {{ Auth::user()->name }}</p>

                <!-- QR Code for Payment -->
                <div class="text-center my-4">
                    <img src="{{asset('assets/qris-image.png')}}" alt="QRIS QR Code" class="img-fluid" style="max-width: 200px;">
                    <p>Scan the QR code to pay</p>
                </div>

                <!-- Payment Confirmation Button -->
                <form action="{{ route('orders.confirmPayment', $commission->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript (if not already included in your layout) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
