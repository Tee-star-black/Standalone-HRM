<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Payslips
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Published Payslips</h3>

                @if ($payslips->isEmpty())
                    <p class="text-gray-500">No payslips available yet.</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Period</th>
                                <th class="border-b p-2">Basic</th>
                                <th class="border-b p-2">Allowances</th>
                                <th class="border-b p-2">Deductions</th>
                                <th class="border-b p-2">Tax</th>
                                <th class="border-b p-2">Net Pay</th>
                                <th class="border-b p-2">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payslips as $payslip)
                                <tr>
                                    <td class="p-2">{{ $payslip->period }}</td>
                                    <td class="p-2">{{ number_format($payslip->basic_salary, 2) }}</td>
                                    <td class="p-2">{{ number_format($payslip->allowances, 2) }}</td>
                                    <td class="p-2">{{ number_format($payslip->deductions, 2) }}</td>
                                    <td class="p-2">{{ number_format($payslip->tax, 2) }}</td>
                                    <td class="p-2 font-bold">{{ number_format($payslip->net_pay, 2) }}</td>

                                    <td class="p-2">
                                        <div style="display:flex; gap:10px;">
                                            <a href="{{ route('my-payslips.show', $payslip) }}"
                                               style="background:#2563eb; color:white; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:bold;">
                                                View
                                            </a>

                                            <a href="{{ route('my-payslips.download', $payslip) }}"
                                               style="background:#16a34a; color:white; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:bold;">
                                                PDF
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>