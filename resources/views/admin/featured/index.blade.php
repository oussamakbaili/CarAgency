@extends('layouts.admin')

@section('title', 'Homepage Content Management')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Homepage Content Management</h1>
        <p class="text-gray-600">Control which cars and agencies appear on the homepage</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabs -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button onclick="showTab('cars')" id="carsTab" class="tab-button active border-orange-500 text-orange-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Cars ({{ $homepageCars->total() }})
                </button>
                <button onclick="showTab('agencies')" id="agenciesTab" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Agencies ({{ $homepageAgencies->total() }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Cars Tab -->
    <div id="carsContent" class="tab-content">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Featured Cars ({{ $featuredCars->count() }})</h2>
            
            @if($featuredCars->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($featuredCars as $car)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h3>
                                    <p class="text-sm text-gray-600">{{ $car->agency->agency_name }}</p>
                                </div>
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded">Featured</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Priority: {{ $car->homepage_priority }}</span>
                                <button onclick="toggleCarFeatured({{ $car->id }})" class="text-red-600 hover:text-red-800">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No featured cars yet</p>
            @endif
        </div>

        <!-- Hidden Cars Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Hidden Cars ({{ \App\Models\Car::where('show_on_homepage', false)->whereHas('agency', function($q) { $q->where('status', 'approved'); })->count() }})</h2>
            
            @php
                $hiddenCars = \App\Models\Car::where('show_on_homepage', false)
                    ->whereHas('agency', function($query) {
                        $query->where('status', 'approved');
                    })
                    ->with(['agency', 'category'])
                    ->orderBy('brand')
                    ->take(10)
                    ->get();
            @endphp
            
            @if($hiddenCars->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($hiddenCars as $car)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition bg-gray-50">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $car->brand }} {{ $car->model }}</h3>
                                    <p class="text-sm text-gray-600">{{ $car->agency->agency_name }}</p>
                                </div>
                                <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs font-medium rounded">Hidden</span>
                            </div>
                            <button onclick="toggleCarHomepage({{ $car->id }})" class="w-full text-center text-green-600 hover:text-green-800 font-medium text-sm py-2 border border-green-600 rounded-lg hover:bg-green-50 transition">
                                Unhide & Show on Homepage
                            </button>
                        </div>
                    @endforeach
                </div>
                @if(\App\Models\Car::where('show_on_homepage', false)->whereHas('agency', function($q) { $q->where('status', 'approved'); })->count() > 10)
                    <p class="text-sm text-gray-500 mt-4 text-center">Showing 10 of {{ \App\Models\Car::where('show_on_homepage', false)->whereHas('agency', function($q) { $q->where('status', 'approved'); })->count() }} hidden cars</p>
                @endif
            @else
                <p class="text-gray-500 text-center py-4">No hidden cars</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">All Homepage Cars</h2>
                <div class="flex gap-2">
                    <select id="bulkCarAction" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                        <option value="">Bulk Actions</option>
                        <option value="feature">Mark as Featured</option>
                        <option value="unfeature">Remove Featured</option>
                        <option value="show">Show on Homepage</option>
                        <option value="hide">Hide from Homepage</option>
                    </select>
                    <button onclick="bulkUpdateCars()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Apply
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAllCars" onclick="toggleAllCars()" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agency</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($homepageCars as $car)
                            <tr>
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="car_ids[]" value="{{ $car->id }}" class="car-checkbox rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($car->image_url)
                                            <img src="{{ $car->image_url }}" alt="{{ $car->brand }}" class="w-12 h-12 rounded object-cover mr-3">
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $car->brand }} {{ $car->model }}</div>
                                            <div class="text-sm text-gray-500">{{ $car->year }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $car->agency->agency_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($car->price_per_day, 0) }} MAD</td>
                                <td class="px-6 py-4">
                                    <input type="number" 
                                           value="{{ $car->homepage_priority }}" 
                                           onchange="updateCarPriority({{ $car->id }}, this.value)"
                                           class="w-20 border border-gray-300 rounded px-2 py-1 text-sm"
                                           min="0" max="100">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @if($car->featured)
                                            <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded">Featured</span>
                                        @endif
                                        @if($car->show_on_homepage)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Visible</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded">Hidden</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button onclick="toggleCarFeatured({{ $car->id }})" 
                                                class="text-orange-600 hover:text-orange-800 font-medium">
                                            {{ $car->featured ? 'Unfeature' : 'Feature' }}
                                        </button>
                                        <button onclick="toggleCarHomepage({{ $car->id }})" 
                                                class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $car->show_on_homepage ? 'Hide' : 'Show' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No cars available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $homepageCars->links() }}
            </div>
        </div>
    </div>

    <!-- Agencies Tab -->
    <div id="agenciesContent" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Featured Agencies ({{ $featuredAgencies->count() }})</h2>
            
            @if($featuredAgencies->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($featuredAgencies as $agency)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $agency->agency_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $agency->city }}</p>
                                </div>
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded">Featured</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Priority: {{ $agency->homepage_priority }}</span>
                                <button onclick="toggleAgencyFeatured({{ $agency->id }})" class="text-red-600 hover:text-red-800">
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No featured agencies yet</p>
            @endif
        </div>

        <!-- Hidden Agencies Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Hidden Agencies ({{ \App\Models\Agency::where('show_on_homepage', false)->where('status', 'approved')->count() }})</h2>
            
            @php
                $hiddenAgencies = \App\Models\Agency::where('show_on_homepage', false)
                    ->where('status', 'approved')
                    ->withCount('cars')
                    ->orderBy('agency_name')
                    ->take(10)
                    ->get();
            @endphp
            
            @if($hiddenAgencies->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($hiddenAgencies as $agency)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition bg-gray-50">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $agency->agency_name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $agency->city }} • {{ $agency->cars_count }} cars</p>
                                </div>
                                <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs font-medium rounded">Hidden</span>
                            </div>
                            <button onclick="toggleAgencyHomepage({{ $agency->id }})" class="w-full text-center text-green-600 hover:text-green-800 font-medium text-sm py-2 border border-green-600 rounded-lg hover:bg-green-50 transition">
                                Unhide & Show on Homepage
                            </button>
                        </div>
                    @endforeach
                </div>
                @if(\App\Models\Agency::where('show_on_homepage', false)->where('status', 'approved')->count() > 10)
                    <p class="text-sm text-gray-500 mt-4 text-center">Showing 10 of {{ \App\Models\Agency::where('show_on_homepage', false)->where('status', 'approved')->count() }} hidden agencies</p>
                @endif
            @else
                <p class="text-gray-500 text-center py-4">No hidden agencies</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">All Homepage Agencies</h2>
                <div class="flex gap-2">
                    <select id="bulkAgencyAction" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
                        <option value="">Bulk Actions</option>
                        <option value="feature">Mark as Featured</option>
                        <option value="unfeature">Remove Featured</option>
                        <option value="show">Show on Homepage</option>
                        <option value="hide">Hide from Homepage</option>
                    </select>
                    <button onclick="bulkUpdateAgencies()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Apply
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAllAgencies" onclick="toggleAllAgencies()" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agency</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cars</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($homepageAgencies as $agency)
                            <tr>
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="agency_ids[]" value="{{ $agency->id }}" class="agency-checkbox rounded border-gray-300">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $agency->agency_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $agency->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $agency->city }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $agency->cars_count }} cars</td>
                                <td class="px-6 py-4">
                                    <input type="number" 
                                           value="{{ $agency->homepage_priority }}" 
                                           onchange="updateAgencyPriority({{ $agency->id }}, this.value)"
                                           class="w-20 border border-gray-300 rounded px-2 py-1 text-sm"
                                           min="0" max="100">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        @if($agency->featured)
                                            <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded">Featured</span>
                                        @endif
                                        @if($agency->show_on_homepage)
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Visible</span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded">Hidden</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex gap-2">
                                        <button onclick="toggleAgencyFeatured({{ $agency->id }})" 
                                                class="text-orange-600 hover:text-orange-800 font-medium">
                                            {{ $agency->featured ? 'Unfeature' : 'Feature' }}
                                        </button>
                                        <button onclick="toggleAgencyHomepage({{ $agency->id }})" 
                                                class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $agency->show_on_homepage ? 'Hide' : 'Show' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No agencies available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $homepageAgencies->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    // Tab switching
    function showTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-orange-500', 'text-orange-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab
        if (tab === 'cars') {
            document.getElementById('carsContent').classList.remove('hidden');
            document.getElementById('carsTab').classList.add('active', 'border-orange-500', 'text-orange-600');
            document.getElementById('carsTab').classList.remove('border-transparent', 'text-gray-500');
        } else {
            document.getElementById('agenciesContent').classList.remove('hidden');
            document.getElementById('agenciesTab').classList.add('active', 'border-orange-500', 'text-orange-600');
            document.getElementById('agenciesTab').classList.remove('border-transparent', 'text-gray-500');
        }
    }

    // Toggle all checkboxes
    function toggleAllCars() {
        const checked = document.getElementById('selectAllCars').checked;
        document.querySelectorAll('.car-checkbox').forEach(checkbox => checkbox.checked = checked);
    }

    function toggleAllAgencies() {
        const checked = document.getElementById('selectAllAgencies').checked;
        document.querySelectorAll('.agency-checkbox').forEach(checkbox => checkbox.checked = checked);
    }

    // Car actions
    async function toggleCarFeatured(carId) {
        try {
            const response = await fetch(`/admin/featured/cars/${carId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function toggleCarHomepage(carId) {
        try {
            const response = await fetch(`/admin/featured/cars/${carId}/toggle-homepage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function updateCarPriority(carId, priority) {
        try {
            const response = await fetch(`/admin/featured/cars/${carId}/priority`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ priority: parseInt(priority) })
            });
            const data = await response.json();
            if (data.success) {
                // Show success message
                console.log('Priority updated');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function bulkUpdateCars() {
        const action = document.getElementById('bulkCarAction').value;
        if (!action) {
            alert('Please select an action');
            return;
        }

        const checkedBoxes = document.querySelectorAll('.car-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Please select at least one car');
            return;
        }

        const carIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/featured/cars/bulk-update';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        carIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'car_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }

    // Agency actions
    async function toggleAgencyFeatured(agencyId) {
        try {
            const response = await fetch(`/admin/featured/agencies/${agencyId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function toggleAgencyHomepage(agencyId) {
        try {
            const response = await fetch(`/admin/featured/agencies/${agencyId}/toggle-homepage`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function updateAgencyPriority(agencyId, priority) {
        try {
            const response = await fetch(`/admin/featured/agencies/${agencyId}/priority`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ priority: parseInt(priority) })
            });
            const data = await response.json();
            if (data.success) {
                console.log('Priority updated');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function bulkUpdateAgencies() {
        const action = document.getElementById('bulkAgencyAction').value;
        if (!action) {
            alert('Please select an action');
            return;
        }

        const checkedBoxes = document.querySelectorAll('.agency-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Please select at least one agency');
            return;
        }

        const agencyIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/featured/agencies/bulk-update';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        agencyIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'agency_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection

