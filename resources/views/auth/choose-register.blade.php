<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-900">Choose Your Account Type</h2>
        <p class="mt-2 text-sm text-gray-600">Select the type of account that best fits your needs</p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 max-w-7xl mx-auto">
        <!-- Client Registration Card -->
        <div class="card-hover bg-white p-10 rounded-2xl shadow-lg border border-gray-200 hover:border-primary-300 transition-all duration-300 min-h-[500px] flex flex-col">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">I'm a Customer</h3>
                <p class="text-lg text-gray-600 leading-relaxed">Create a customer account to rent cars from our partner agencies</p>
            </div>
            
            <ul class="space-y-4 mb-10 flex-grow">
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Access to all available cars
                </li>
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Simple online booking
                </li>
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Best price comparison
                </li>
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    24/7 customer support
                </li>
            </ul>
            
            <a href="{{ route('register.client') }}" class="btn-primary w-full flex justify-center py-4 px-6 border border-transparent rounded-lg text-base font-medium text-white transition duration-200 hover:no-underline mt-auto">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Register as Customer
            </a>
        </div>

        <!-- Agency Registration Card -->
        <div class="card-hover bg-white p-10 rounded-2xl shadow-lg border border-gray-200 hover:border-accent-300 transition-all duration-300 min-h-[500px] flex flex-col">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-accent-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-4">I'm an Agency</h3>
                <p class="text-lg text-gray-600 leading-relaxed">Create an agency account to manage your vehicle fleet and rentals</p>
            </div>
            
            <ul class="space-y-4 mb-10 flex-grow">
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Complete fleet management
                </li>
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Professional dashboard
                </li>
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Analytics and insights
                </li>
                <li class="flex items-center text-base text-gray-700">
                    <svg class="w-6 h-6 mr-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Secure payment processing
                </li>
            </ul>
            
            <a href="{{ route('register.agency') }}" class="btn-secondary w-full flex justify-center py-4 px-6 border border-transparent rounded-lg text-base font-medium text-white transition duration-200 hover:no-underline mt-auto">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Register as Agency
            </a>
        </div>
    </div>

    <div class="mt-8 text-center">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 transition duration-200">
                Sign in here
            </a>
        </p>
    </div>
</x-guest-layout>

