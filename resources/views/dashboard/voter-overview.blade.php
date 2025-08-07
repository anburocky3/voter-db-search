<x-layouts.app>
            <h1 class="text-2xl font-bold mb-6">Voter Data Overview</h1>
            <form method="GET" class="mb-6">
                <label for="ac_no" class="block text-sm font-medium mb-2">Assembly Constituency</label>
                <select name="ac_no" id="ac_no" class="input-txt" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($availableACs as $ac)
                        <option value="{{ $ac }}" @if($acNo == $ac) selected @endif>{{ $ac }}</option>
                    @endforeach
                </select>
            </form>

            @if($acNo && $genderData && $status && $ageGroups && $wardWise && $acWise)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Gender Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Gender Distribution</h2>
                    <canvas id="genderChart"></canvas>
                </div>
                <!-- Age Groups -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Age Groups</h2>
                    <canvas id="ageChart"></canvas>
                </div>
                <!-- Status Types -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Status Types</h2>
                    <canvas id="statusChart"></canvas>
                </div>
                <!-- Ward-wise Voters -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Voters per Ward</h2>
                    <canvas id="wardChart"></canvas>
                </div>
                <!-- AC-wise Voters -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 md:col-span-2">
                    <h2 class="text-lg font-semibold mb-4">Voters per Assembly Constituency</h2>
                    <canvas id="acChart"></canvas>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
            <script>

                const realGenderData = {
                    Male: {{ $genderData['Male'] }},
                    Female: {{ $genderData['Female'] }},
                    Others: {{ $genderData['Others'] }}
                };
                const total = realGenderData.Male + realGenderData.Female + realGenderData.Others;
                const minPercent = 0.01; // 1%
                const minValue = Math.ceil(total * minPercent);

                // Adjusted data for chart display
                let displayOthers = realGenderData.Others;
                let displayMale = realGenderData.Male;
                let displayFemale = realGenderData.Female;

                if (realGenderData.Others > 0 && realGenderData.Others < minValue) {
                    const diff = minValue - realGenderData.Others;
                    // Reduce from the largest group to keep total the same
                    if (displayMale >= displayFemale) {
                        displayMale -= diff;
                    } else {
                        displayFemale -= diff;
                    }
                    displayOthers = minValue;
                }

                new Chart(document.getElementById('genderChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Male', 'Female', 'Others'],
                        datasets: [{
                            data: [displayMale, displayFemale, displayOthers],
                            backgroundColor: ['#3b82f6', '#f472b6', '#fbbf24'],
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        // Show real value in tooltip
                                        const label = context.label || '';
                                        let value = realGenderData[label];
                                        return label + ': ' + value;
                                    }
                                }
                            }
                        }
                    }
                });

                // Age Chart
                new Chart(document.getElementById('ageChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($ageGroups)) !!},
                        datasets: [{
                            label: 'Age Group Distribution',
                            data: {!! json_encode(array_values($ageGroups)) !!},
                            backgroundColor: [
                                '#6366f1', // Indigo
                                '#10b981', // Emerald
                                '#f59e42', // Orange
                                '#ef4444'  // Red
                            ],
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                color: '#FFF',
                                font: {
                                    weight: 'bold'
                                },
                                formatter: function(value) {
                                    return value;
                                }
                            }
                        }
                    },
                    plugins: [ChartDataLabels]
                });

                // Status Chart
                new Chart(document.getElementById('statusChart'), {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode(array_keys($status->toArray())) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($status->toArray())) !!},
                            backgroundColor: [
                                '#0ea5e9', // Sky
                                '#a21caf', // Purple
                                '#fbbf24', // Yellow
                                '#22d3ee', // Cyan
                                '#f43f5e', // Rose
                                '#84cc16'  // Lime
                            ],
                        }]
                    }
                });

                // Ward Chart
                new Chart(document.getElementById('wardChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($wardWise->toArray())) !!},
                        datasets: [{
                            label: 'Voters',
                            data: {!! json_encode(array_values($wardWise->toArray())) !!},
                            backgroundColor: '#f59e42',
                        }]
                    }
                });

                // AC Chart
                new Chart(document.getElementById('acChart'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($acWise->toArray())) !!},
                        datasets: [{
                            label: 'Voters',
                            data: {!! json_encode(array_values($acWise->toArray())) !!},
                            backgroundColor: '#0ea5e9',
                        }]
                    }
                });
            </script>
            @endif
        </x-layouts.app>
