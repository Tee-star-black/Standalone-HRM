<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Profile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-4 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Personal Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <p><strong>Name:</strong> {{ $employee->full_name }}</p>
                    <p><strong>Email:</strong> {{ $employee->email }}</p>
                    <p><strong>Phone:</strong> {{ $employee->phone ?? '-' }}</p>
                    <p><strong>Employee Number:</strong> {{ $employee->employee_number }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($employee->status) }}</p>
                    <p><strong>Hire Date:</strong> {{ $employee->hire_date?->format('Y-m-d') ?? '-' }}</p>
                    <p><strong>Department:</strong> {{ $employee->department?->name ?? '-' }}</p>
                    <p><strong>Manager:</strong> {{ $employee->manager?->full_name ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Employment Details</h3>

                @if ($employee->positions->isEmpty())
                    <p class="text-gray-500">No position assigned.</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Position</th>
                                <th class="border-b p-2">Job</th>
                                <th class="border-b p-2">Status</th>
                                <th class="border-b p-2">Primary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->positions as $position)
                                <tr>
                                    <td class="p-2">{{ $position->title }}</td>
                                    <td class="p-2">{{ $position->job?->title ?? '-' }}</td>
                                    <td class="p-2">{{ ucfirst($position->status) }}</td>
                                    <td class="p-2">{{ $position->is_primary ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Add Emergency Contact / Next of Kin</h3>

                <form method="POST" action="{{ route('my-profile.emergency-contacts.store') }}" class="space-y-4">
                    @csrf

                    <input name="name" required placeholder="Full name" class="w-full border rounded p-2">
                    <input name="relationship" placeholder="Relationship" class="w-full border rounded p-2">
                    <input name="phone" required placeholder="Phone" class="w-full border rounded p-2">
                    <input name="email" placeholder="Email" class="w-full border rounded p-2">
                    <textarea name="address" placeholder="Address" class="w-full border rounded p-2"></textarea>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_primary" value="1">
                        Primary contact
                    </label>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded">
                        Save Contact
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Emergency Contacts</h3>

                @if ($employee->emergencyContacts->isEmpty())
                    <p class="text-gray-500">No emergency contacts added.</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Name</th>
                                <th class="border-b p-2">Relationship</th>
                                <th class="border-b p-2">Phone</th>
                                <th class="border-b p-2">Email</th>
                                <th class="border-b p-2">Primary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employee->emergencyContacts as $contact)
                                <tr>
                                    <td class="p-2">{{ $contact->name }}</td>
                                    <td class="p-2">{{ $contact->relationship ?? '-' }}</td>
                                    <td class="p-2">{{ $contact->phone }}</td>
                                    <td class="p-2">{{ $contact->email ?? '-' }}</td>
                                    <td class="p-2">{{ $contact->is_primary ? 'Yes' : 'No' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>