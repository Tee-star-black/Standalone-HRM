<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            HR Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-4 rounded shadow">
                    <p class="text-sm text-gray-500">Employees</p>
                    <h2 class="text-2xl font-bold">{{ $employeesCount ?? 0 }}</h2>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <p class="text-sm text-gray-500">Jobs</p>
                    <h2 class="text-2xl font-bold">{{ $jobsCount ?? 0 }}</h2>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <p class="text-sm text-gray-500">Positions</p>
                    <h2 class="text-2xl font-bold">{{ $positionsCount ?? 0 }}</h2>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <p class="text-sm text-gray-500">Skills</p>
                    <h2 class="text-2xl font-bold">{{ $skillsCount ?? 0 }}</h2>
                </div>
            </div>

            <!-- Skill Gaps -->
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Top Skill Gaps</h3>

                @if(!empty($gapEmployees) && count($gapEmployees))
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Employee</th>
                                <th class="border-b p-2">Job</th>
                                <th class="border-b p-2">Gap</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gapEmployees as $item)
                                <tr>
                                    <td class="p-2">{{ $item['employee']->full_name ?? $item['employee']->first_name }}</td>
                                    <td class="p-2">{{ $item['job']->title }}</td>
                                    <td class="p-2 font-bold text-red-500">{{ $item['total_gap'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No skill gap data available.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>