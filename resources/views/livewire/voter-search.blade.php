<div class=" px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Voter Data Search</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-300">Search and filter from {{ number_format($totalRecords) }} voter records</p>

    </div>

    <!-- Search Bar -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-3">
                <label for="acNo" class="block text-sm font-medium text-gray-700 mb-2">Assembly Constituency</label>
                <select wire:model="acNo" id="acNo" class="input-txt">
                    <option value="">All</option>
                    @foreach($availableACs as $ac)
                        <option value="{{ $ac }}">{{ $ac }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input
                    wire:model.live.debounce.500ms="search"
                    type="text"
                    id="search"
                    placeholder="Enter search term..."
                    class="input-txt"
                >
            </div>
            <div>
                <label for="searchField" class="block text-sm font-medium text-gray-700 mb-2">Search Field</label>
                <select
                    wire:model.live="searchField"
                    id="searchField"
                    class="py-2 px-4 bg-gray-100 text-black w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                >
                    <option value="all">All Fields</option>
                    <option value="FM_NAME_EN">First Name</option>
                    <option value="LASTNAME_EN">Last Name</option>
                    <option value="IDCARD_NO">ID Card Number</option>
                    <option value="UIDNO">UID Number</option>
                    <option value="MOBILENO">Mobile Number</option>
                    <option value="HOUSE_NO_EN">House Number</option>
                </select>
            </div>
        </div>

        <!-- Filter Toggle -->
        <div class="mt-4 flex justify-between items-center">
            <button
                wire:click="$toggle('showFilters')"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                </svg>
                {{ $showFilters ? 'Hide Filters' : 'Show Filters' }}
            </button>

            @if($hasSearchCriteria)
                <button
                    wire:click="resetFilters"
                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    Clear All Filters
                </button>
            @endif
        </div>
    </div>

    <!-- Advanced Filters -->
    @if($showFilters)
        <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Advanced Filters</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- AC Number -->
                <div>
                    <label for="acNo" class="block text-sm font-medium text-gray-700 mb-1">AC Number</label>
                    <input
                        wire:model.live.debounce.300ms="acNo"
                        type="number"
                        id="acNo"
                        class="input-txt"
                    >
                </div>

                <!-- Part Number -->
                <div>
                    <label for="partNo" class="block text-sm font-medium text-gray-700 mb-1">Part Number</label>
                    <input
                        wire:model.live.debounce.300ms="partNo"
                        type="number"
                        id="partNo"
                        class="input-txt"
                    >
                </div>

                <!-- Ward Number -->
                <div>
                    <label for="wardNo" class="block text-sm font-medium text-gray-700 mb-1">Ward Number</label>
                    <input
                        wire:model.live.debounce.300ms="wardNo"
                        type="number"
                        id="wardNo"
                        class="input-txt"
                    >
                </div>

                <!-- Gender -->
                <div>
                    <label for="sex" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select
                        wire:model.live="sex"
                        id="sex"
                        class="input-txt"
                    >
                        <option value="">All</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>

                <!-- Age Range -->
                <div>
                    <label for="ageFrom" class="block text-sm font-medium text-gray-700 mb-1">Age From</label>
                    <input
                        wire:model.live.debounce.300ms="ageFrom"
                        type="number"
                        id="ageFrom"
                        min="18"
                        max="120"
                        class="input-txt"
                    >
                </div>

                <div>
                    <label for="ageTo" class="block text-sm font-medium text-gray-700 mb-1">Age To</label>
                    <input
                        wire:model.live.debounce.300ms="ageTo"
                        type="number"
                        id="ageTo"
                        min="18"
                        max="120"
                        class="input-txt"
                    >
                </div>

                <!-- Status Type -->
                <div>
                    <label for="statusType" class="block text-sm font-medium text-gray-700 mb-1">Status Type</label>
                    <select
                        wire:model.live="statusType"
                        id="statusType"
                        class="input-txt"
                    >
                        <option value="">All</option>
                        <option value="N">New</option>
                        <option value="A">Active</option>
                        <option value="D">Deleted</option>
                    </select>
                </div>

                <!-- Relationship Type -->
                <div>
                    <label for="relationshipType"
                           class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                    <select
                        wire:model.live="relationshipType"
                        id="relationshipType"
                        class="input-txt"
                    >
                        <option value="">All</option>
                        <option value="F">Father</option>
                        <option value="H">Husband</option>
                        <option value="M">Mother</option>
                        <option value="W">Wife</option>
                        <option value="O">Other</option>
                    </select>
                </div>
            </div>
        </div>
    @endif

    @if(!$hasSearchCriteria)
        <!-- No Search Criteria Message -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <div class="flex flex-col items-center">
                <svg class="h-12 w-12 text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-blue-900 mb-2">Start Your Search</h3>
                <p class="text-blue-700 mb-4">Enter search terms or apply filters to find voter records from our
                                              database of {{ number_format($totalRecords) }} records.</p>
                <button
                    wire:click="$set('showFilters', true)"
                    class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Show Advanced Filters
                </button>
            </div>
        </div>
    @else
        <!-- Results Summary -->
        @if($voters->count() > 0)
            <div class="bg-white shadow-sm rounded-lg p-4 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">
                                Showing {{ $voters->firstItem() }} to {{ $voters->lastItem() }} of <strong>{{ number_format($voters->total()) }} results</strong>
                            </span>
                    </div>

                    <div class="mt-3 sm:mt-0">
                        <select
                            wire:model.live="perPage"
                            class="input-txt"
                        >
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                            <option value="100">100 per page</option>
                        </select>
                    </div>
                </div>
            </div>

    <!-- Results Table -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th wire:click="sortBy('SLNOINPART')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center space-x-1">
                                        <span>Serial No.</span>
                                        @if($sortField === 'SLNOINPART')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                @if($sortDirection === 'asc')
                                                    <path fill-rule="evenodd"
                                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                          clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('FM_NAME_EN')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center space-x-1">
                                        <span>Name</span>
                                        @if($sortField === 'FM_NAME_EN')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                @if($sortDirection === 'asc')
                                                    <path fill-rule="evenodd"
                                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                          clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Address.
                                </th>
                                <th wire:click="sortBy('AC_NO')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center space-x-1">
                                        <span>AC/Part/Ward</span>
                                        @if($sortField === 'AC_NO')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                @if($sortDirection === 'asc')
                                                    <path fill-rule="evenodd"
                                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                          clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('AGE')"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                    <div class="flex items-center space-x-1">
                                        <span>Age/Gender</span>
                                        @if($sortField === 'AGE')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                @if($sortDirection === 'asc')
                                                    <path fill-rule="evenodd"
                                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                          clip-rule="evenodd" />
                                                @else
                                                    <path fill-rule="evenodd"
                                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                          clip-rule="evenodd" />
                                                @endif
                                            </svg>
                                        @endif
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID Numbers
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($voters as $voter)
                               <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-500">
                                        {{ $voter->SLNOINPART }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-5 w-5 text-blue-500 dark:text-blue-300" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-500">
                                                    {{ $voter->FM_NAME_EN }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $voter->RLN_TYPE }} - {{ $voter->RLN_FM_NM_EN }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-400">
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-5 w-5 text-yellow-500 dark:text-yellow-300" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                                            </svg>
                                            <span>{{ $voter->address }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-400">
                                        <div class="flex items-center space-x-2">
                                            <svg class="h-5 w-5 text-purple-500 dark:text-purple-300" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M17.657 16.657L13.414 12.414a2 2 0 00-2.828 0l-4.243 4.243A8 8 0 1117.657 16.657z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>{{ $voter->AC_NO }}/{{ $voter->PART_NO }}/{{ $voter->WARD_NO }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-400">
                                        <div class="flex items-center space-x-2">
                                            @if($voter->SEX === 'M')
                                                <svg class="h-5 w-5 text-blue-400 dark:text-blue-200" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M17 7V3m0 0h4m-4 0l-5 5m-2 2a7 7 0 11-2 2" />
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-pink-400 dark:text-pink-200" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2"
                                                            fill="none" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M12 17v5m0 0h-2m2 0h2" />
                                                </svg>
                                            @endif
                                            <span>{{ $voter->AGE }} / {{ $voter->SEX === 'M' ? 'Male' : 'Female' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        @if($voter->IDCARD_NO)
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-4 w-4 text-green-500 dark:text-green-300" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <rect width="18" height="12" x="3" y="6" rx="2"
                                                          stroke="currentColor" stroke-width="2" fill="none" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M7 10h.01M7 14h.01M11 10h2" />
                                                </svg>
                                                <span>ID: {{ $voter->IDCARD_NO }}</span>
                                            </div>
                                        @endif
                                        @if($voter->UIDNO)
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-4 w-4 text-indigo-500 dark:text-indigo-300" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="10" stroke="currentColor"
                                                            stroke-width="2" fill="none" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01" />
                                                </svg>
{{--                                                 <span>UID: {{ Str::mask($voter->UIDNO, '*', 4, 4) }}</span> --}}
                                                <span>UID: {{ $voter->UIDNO }}</span>
                                            </div>
                                        @endif
                                        @if($voter->MOBILENO)
                                            <div class="flex items-center space-x-1">
                                                <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <rect width="16" height="20" x="4" y="2" rx="2"
                                                          stroke="currentColor" stroke-width="2" fill="none" />
                                                    <circle cx="12" cy="18" r="1" stroke="currentColor" stroke-width="2"
                                                            fill="none" />
                                                </svg>
                                                <span>Mobile: {{ $voter->MOBILENO }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $voter->STATUSTYPE === 'A' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                               ($voter->STATUSTYPE === 'N' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                            @if($voter->STATUSTYPE === 'A')
                                                <svg class="h-4 w-4 mr-1 text-green-500 dark:text-green-200"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Active
                                            @elseif($voter->STATUSTYPE === 'N')
                                                <svg class="h-4 w-4 mr-1 text-blue-500 dark:text-blue-200"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                New
                                            @else
                                                <svg class="h-4 w-4 mr-1 text-red-500 dark:text-red-200" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Deleted
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($voters->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $voters->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- No Results -->
            <div class="bg-white shadow-sm rounded-lg p-12 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Results Found</h3>
                <p class="text-gray-500 mb-4">No voter records match your search criteria. Try adjusting your filters or
                                              search terms.</p>
                <button
                    wire:click="resetFilters"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Clear Filters
                </button>
            </div>
        @endif
    @endif

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 flex flex-col items-center space-y-3 border border-gray-200 dark:border-gray-700">
            <svg class="animate-spin h-8 w-8 text-blue-600 dark:text-blue-400 mb-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 dark:text-gray-200 text-lg font-medium">Searching...</span>
        </div>
    </div>
</div>
