<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" id="availability">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-base mb-2">Week: {{ $week }} - {{ $year }} | {{ auth()->user()->name }}</h1>
            <div class="border-b-2 rounded-tl-lg rounded-tr-lg p-2 clearfix">
                <a class="float-left" href="/availability?year={{ $year }}&week={{ $week - 1 }}">Vorige week</a>
                <a class="float-right" href="/availability?year={{ $year }}&week={{ $week + 1 }}">Volgende week</a>
            </div>
            <div class="mt-2">
                @foreach($dates as $date)
                <div class="flex
                 @if(count($date['availableUsers']) > 0 && count($date['availableUsers']) < 6) bg-green-200 @endif
                 @if(count($date['availableUsers']) > 5 && count($date['availableUsers']) < 8) bg-orange-200 @endif
                 @if(count($date['availableUsers']) > 8) bg-red-200 @endif
                 overflow-hidden shadow-xl sm:rounded-lg mb-12 p-8">
                    <div class="w-1/2">
                        <div class="text-2xl">
                            {{ $date['translated'] }}
                        </div>
                        <div class="flex mb-4 items-stretch w-full">
                            <p class="text-lg text-xs text-gray-700 ml-12">Afwezig</p>
                            <div class="ml-12">
                                <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input
                                        type="checkbox"
                                        @if(isset($datesUser[$date['identifier']]))
                                            checked
                                        @endif
                                        name="aanwezig[{{ $date['identifier'] }}]"
                                        id="toggle[{{ $date['identifier'] }}]"
                                        onchange="changeAvailability(event, '{{ $date['identifier'] }}')"
                                        class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                    />
                                    <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                </div>
                            </div>
                            <p class="text-lg text-xs ml-12 text-gray-700">Aanwezig</p>
                        </div>
                    </div>
                    <div class="w-1/2 flex flex-row float-right ml-8">
                        <p class="text-lg text-xs ml-12 text-gray-600">Aanwezig</p>
                        @foreach($date['availableUsers'] as $users)
                            <img class="h-8 w-8 rounded-full object-cover ml-2" src="https://ui-avatars.com/api/?name={{ $users->name }}&amp;color=7F9CF5&amp;background=EBF4FF" alt="{{ $users->name }}" />
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

<script type="text/javascript">
    function changeAvailability(e, dateAvailability) {
        const {checked} = e.target;

        if (checked) {
            axios.post('/availability/store', {
                user_id: {{ Auth::user()->id }},
                date_availability: dateAvailability,
                at_office: true
            })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        } else {
            axios.post('/availability/destroy', {
                user_id: {{ Auth::user()->id }},
                date_availability: dateAvailability,
                at_office: false
            })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }
</script>
