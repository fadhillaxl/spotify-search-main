<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SpotifyService;
use App\Events\SongRequestCreated;
use App\Models\SpotifyApiCredential;
use Illuminate\Support\Facades\Auth;

class SpotifyController extends Controller
{
    protected $spotify;

    public function __construct(SpotifyService $spotify)
    {
        $this->spotify = $spotify;
        // Don't set a user, which will make the service use application credentials by default
    }

    /**
     * Display the search page
     */
    public function index()
    {
        // dd($this->spotify->forUser(Auth::user()));
        return view('search');
    }

    public function searchRequest($bandname)
    {
        $userId = \App\Models\User::where('name', $bandname)->value('id');
        $activeplaylist = \App\Models\Playlist::where('user_id', $userId)->where('is_active', true)->first();
        $activeplaylistId = $activeplaylist ? $activeplaylist->spotify_playlist_id : null;
        // dd($activeplaylistId);
        return view('request', compact('bandname', 'activeplaylistId'));
    }

    public function searchRequestCommerce($bandname)
    {
        $userId = \App\Models\User::where('name', $bandname)->value('id');
        $activeplaylist = \App\Models\Playlist::where('user_id', $userId)->where('is_active', true)->first();
        $activeplaylistId = $activeplaylist ? $activeplaylist->spotify_playlist_id : null;
        return view('request-commerce', compact('bandname', 'activeplaylistId'));
    }

    /**
     * Search for tracks
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $results = $this->spotify->searchTracks($request->query('query'));
        return response()->json($results);
    }
    
    /**
     * Get tracks from a specific playlist
     */
    public function playlistTracks($playlistId)
    {
        $results = $this->spotify->getPlaylistTracks($playlistId);
        return response()->json($results);
    }
    
    /**
     * Search for tracks within a specific playlist
     */
    public function searchPlaylist(Request $request, $playlistId)
    {
        $request->validate([
            'query' => 'required|string',
        ]);
        
        $results = $this->spotify->searchPlaylistTracks($playlistId, $request->query('query'));
        
        return response()->json($results);
    }
    
    /**
     * Store a song request
     */
    // public function storeSongRequest(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'name' => 'required|string|max:255',
    //             'song_name' => 'required|string|max:255',
    //             'artist' => 'nullable|string|max:255',
    //             'email' => 'required|email',
    //             'amount' => 'required|numeric|min:10000',
    //         ]);
    //         $userId = \App\Models\User::where('name', $validated['name'])->value('id');

    //         if (!$userId) {
    //             return back()->withErrors(['name' => 'User not found.']);
    //         }
    //         $songRequest = \App\Models\SongRequest::create([
    //             'name' => $validated['name'],
    //             'user_id' => $userId,
    //             'song_name' => $validated['song_name'],
    //             'artist' => $validated['artist'],
    //             'status' => 'pending',
    //         ]);

    //         // Broadcast the new request event
    //         event(new SongRequestCreated($songRequest));

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Song request submitted successfully',
    //             'data' => $songRequest
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Failed to store song request', [
    //             'error' => $e->getMessage(),
    //             'request' => $request->all()
    //         ]);

    //         if ($request->input('amount') < 10000) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Oppss minimal Rp.10000 guys'
    //             ], 400);
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to submit song request: ' . $e->getMessage() . '. Please try again later.'
    //         ], 500);
    //     }
    // }

    public function storeSongRequest(Request $request)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'song_name' => 'required|string|max:255',
            'artist' => 'nullable|string|max:255',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:100',
        ]);

        // Cari atau buat user berdasarkan name
        $user = \App\Models\User::firstOrCreate(
            ['name' => $validated['name']],
            ['email' => $validated['email']] // Pastikan email tersedia
        );

        // Membuat request lagu baru dan otomatis menghubungkannya ke user
        $songRequest = $user->songRequests()->create([
            'name' => $validated['name'], // Pastikan 'name' terisi
            'song_name' => $validated['song_name'],
            'artist' => $validated['artist'],
            'status' => 'pending',
        ]);

        // Broadcast event
        event(new SongRequestCreated($songRequest));

        // Return response sukses
        return response()->json([
            'success' => true,
            'message' => 'Song request submitted successfully',
            'data' => $songRequest
        ]);

    } catch (\Exception $e) {
        \Log::error('Failed to store song request', [
            'error' => $e->getMessage(),
            'request' => $request->all()
        ]);

        // Handle if amount is less than 10000
        if ($request->input('amount') < 10000) {
            return response()->json([
                'success' => false,
                'message' => 'Oppss minimal Rp.10000 guys'
            ], 400);
        }

        // Return error response
        return response()->json([
            'success' => false,
            'message' => 'Failed to submit song request: ' . $e->getMessage() . '. Please try again later.'
        ], 500);
    }
}

    
    /**
     * Store Spotify API credentials for the authenticated user
     */
    public function storeCredentials(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|string|max:255',
            'client_secret' => 'required|string|max:255',
        ]);
        
        try {
            $user = Auth::user();
            
            // Deactivate any existing active credentials
            SpotifyApiCredential::where('user_id', $user->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
            
            // Create new credentials
            $credentials = SpotifyApiCredential::create([
                'user_id' => $user->id,
                'client_id' => $validated['client_id'],
                'client_secret' => $validated['client_secret'],
                'is_active' => true,
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Spotify API credentials stored successfully',
                    'data' => $credentials
                ]);
            }
            
            return redirect()->back()->with('success', 'Spotify API credentials stored successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to store Spotify credentials', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to store Spotify credentials'
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to store Spotify credentials']);
        }
    }
    
    /**
     * Display the Spotify API credentials page
     */
    public function credentialsPage()
    {
        $user = Auth::user();
        $credentials = $user->spotifyApiCredential;
        
        return view('spotify.credentials', [
            'credentials' => $credentials,
        ]);
    }
}
