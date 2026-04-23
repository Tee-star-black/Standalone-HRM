<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standalone HRM Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Standalone HRM Dashboard</h1>
            <p class="text-gray-600 mt-2">Overview of your HR system</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm text-gray-500">Employees</p>
                <h2 class="text-3xl font-bold mt-2">{{ $employeesCount }}</h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm text-gray-500">Jobs</p>
                <h2 class="text-3xl font-bold mt-2">{{ $jobsCount }}</h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm text-gray-500">Positions</p>
                <h2 class="text-3xl font-bold mt-2">{{ $positionsCount }}</h2>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm text-gray-500">Skills</p>
                <h2 class="text-3xl font-bold mt-2">{{ $skillsCount }}</h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900">Top Skill Gaps</h2>
            </div>

            @if($gapEmployees->isEmpty())
                <p class="text-gray-500">No skill gap data available.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Employee</th>
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Job</th>
                                <th class="text-left py-3 px-2 text-sm font-semibold text-gray-700">Total Gap</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gapEmployees as $item)
                                <tr class="border-b last:border-b-0">
                                    <td class="py-3 px-2">{{ $item['employee']->full_name }}</td>
                                    <td class="py-3 px-2">{{ $item['job']->title }}</td>
                                    <td class="py-3 px-2 font-semibold">{{ $item['total_gap'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>