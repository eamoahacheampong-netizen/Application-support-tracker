<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Support Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6 text-center">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Volume</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $activities->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6 text-center">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pending / Active</div>
                    <div class="mt-2 text-3xl font-bold text-blue-600">{{ $activities->whereIn('status', ['Pending', 'In Progress'])->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6 text-center">
                    <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Resolved</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">{{ $activities->where('status', 'Completed')->count() }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-6 text-gray-900 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-gray-900">Create Support Ticket</h3>
                    <form action="{{ route('activities.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Issue Summary</label>
                                <input type="text" name="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Severity Level</label>
                                <select name="severity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Low">Low - Minor issue, no immediate impact</option>
                                    <option value="Medium" selected>Medium - Noticeable impact, manageable</option>
                                    <option value="High">High - Critical failure, immediate action required</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Technical Details</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition shadow-sm">
                                Submit Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Active Queue</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Ticket Identifier & Log</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">State</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50 transition align-top">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $activity->title }}</div>
                                    <div class="text-sm text-gray-500 mt-1 mb-4">{{ $activity->description }}</div>
                                    
                                    <div class="bg-gray-100 p-3 rounded-md border border-gray-200">
                                        <h4 class="text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Activity Log</h4>
                                        
                                        @foreach($activity->comments as $comment)
                                            <div class="text-sm text-gray-800 mb-4 border-l-2 border-indigo-400 pl-2">
                                                <div>
                                                    <span class="font-bold">{{ $comment->user->name }}:</span> {{ $comment->body }}
                                                    <span class="text-xs text-gray-500 ml-1">({{ $comment->created_at->diffForHumans() }})</span>
                                                </div>
                                                
                                                <!-- NEW: Display the image if it exists -->
                                                @if($comment->file_path)
                                                    <div class="mt-2">
                                                        <a href="{{ Storage::url($comment->file_path) }}" target="_blank">
                                                            <img src="{{ Storage::url($comment->file_path) }}" alt="Ticket Attachment" class="max-w-xs rounded border border-gray-200 shadow-sm hover:opacity-80 transition">
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach

                                        <!-- UPDATED FORM STARTS HERE -->
                                        <form action="{{ route('comments.store', $activity) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                                            @csrf
                                            <div class="flex gap-2 mb-2">
                                                <input type="text" name="body" required placeholder="Log troubleshooting steps..." class="text-sm w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                <button type="submit" class="bg-gray-800 hover:bg-gray-700 text-white text-sm font-bold py-1 px-4 rounded shadow-sm">
                                                    Post
                                                </button>
                                            </div>
                                            
                                            <div class="mt-2">
                                                <input type="file" name="attachment" accept="image/*,.pdf" class="text-xs text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                            </div>
                                        </form>
                                        <!-- UPDATED FORM ENDS HERE -->

                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($activity->severity == 'High')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">High</span>
                                    @elseif($activity->severity == 'Medium')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Low</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $activity->created_at->format('M j, Y - H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('activities.update', $activity) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="Pending" {{ $activity->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $activity->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Completed" {{ $activity->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($activities->isEmpty())
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">No tickets in the current queue.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>