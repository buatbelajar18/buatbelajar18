<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Models\User;

class ArtistController extends Controller
{
    // Display a list of artists (users with commissions)
    public function index()
    {
        // Retrieve all users who have posted commissions, including commission and review data
        $artists = User::whereHas('commissions')->with('commissions.reviews.user')->get();

        // Pass the artist data to the 'artists.index' view
        return view('artists.index', compact('artists'));
    }

    // Search function for an artist based on the user ID
    public function search($id)
    {
        // Find the user by ID and load related commissions and reviews
        $artist = User::whereHas('commissions')->with('commissions.reviews.user')->find($id);

        // Check if the artist (user) exists
        if (!$artist) {
            return redirect()->route('artists.index')->with('error', 'Artist not found');
        }

        // Return a view to display the artist's details (create a view named 'artists.show' or similar)
        return view('artists.show', compact('artist'));
    }
}
