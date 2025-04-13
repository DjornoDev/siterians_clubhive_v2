@section('title', $club->club_name . ' - Elections')
@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-8">Club Election</h1>
        <p class="text-center mb-8">Cast your vote for the new club officers. Select one candidate for each position.</p>

        <!-- Results Section -->
        <div class="mb-10 bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-center">Current Results</h2>
            <p class="text-center text-sm text-gray-600 mb-6">Updated every 5 minutes. Last update: <span
                    id="lastUpdate">April 8, 2025 10:30 AM</span></p>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- President Results -->
                <div>
                    <h3 class="font-bold mb-3">President</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Pepe Smith (BUSORG)</span>
                                <span>58%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 58%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Pedro Penduko (IRI)</span>
                                <span>42%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vice President Results -->
                <div>
                    <h3 class="font-bold mb-3">Vice President</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Rain Pogi (BUSORG)</span>
                                <span>45%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Jasper Mas Pogi (IRI)</span>
                                <span>55%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 55%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secretary Results -->
                <div>
                    <h3 class="font-bold mb-3">Secretary</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Mikasa Ackerman (BUSORG)</span>
                                <span>62%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 62%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Umaru Chan (IRI)</span>
                                <span>38%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 38%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Treasurer Results -->
                <div>
                    <h3 class="font-bold mb-3">Treasurer</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Satoru Gojo (BUSORG)</span>
                                <span>71%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 71%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Lelouch Vi Britannia (IRI)</span>
                                <span>29%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 29%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PIO Results -->
                <div>
                    <h3 class="font-bold mb-3">Public Information Officer</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Carlos Reyes (BUSORG)</span>
                                <span>49%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 49%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Sofia Garcia (IRI)</span>
                                <span>51%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 51%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Auditor Results -->
                <div>
                    <h3 class="font-bold mb-3">Auditor</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Kakashi Hatake (BUSORG)</span>
                                <span>53%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 53%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Eren Jaeger (IRI)</span>
                                <span>47%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 47%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Protocol Officer Results -->
                <div>
                    <h3 class="font-bold mb-3">Protocol Officer</h3>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>David Tan (BUSORG)</span>
                                <span>42%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 42%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span>Isabella Santos (IRI)</span>
                                <span>58%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 58%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Total Votes Cast: 387</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- President -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">PRESIDENT</h2>
                    <button type="button" class="text-blue-500" onclick="resetSelection('president')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm mb-4">You may only select 1 candidate.</p>

                <div class="space-y-4">
                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="president-1" name="president" value="Pepe Smith" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Pepe Smith"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Pepe Smith</h3>
                            <p class="text-sm text-gray-600">BUSORG PARTYLIST</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="president-2" name="president" value="Pedro Penduko" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Pedro Penduko"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Pedro Penduko</h3>
                            <p class="text-sm text-gray-600">IRI PARTYLIST</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700"
                        onclick="submitVote('president')">
                        Submit
                    </button>
                </div>
            </div>

            <!-- Vice President -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">VICE PRESIDENT</h2>
                    <button type="button" class="text-blue-500" onclick="resetSelection('vicePresident')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm mb-4">You may only select 1 candidate.</p>

                <div class="space-y-4">
                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="vicePresident-1" name="vicePresident" value="Rain Pogi"
                            class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/22.jpg" alt="Rain Pogi"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Rain Pogi</h3>
                            <p class="text-sm text-gray-600">BUSORG PARTYLIST</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="vicePresident-2" name="vicePresident" value="Jasper Mas Pogi"
                            class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/55.jpg" alt="Jasper Mas Pogi"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Jasper Mas Pogi</h3>
                            <p class="text-sm text-gray-600">IRI PARTYLIST</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700"
                        onclick="submitVote('vicePresident')">
                        Submit
                    </button>
                </div>
            </div>

            <!-- Secretary -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">SECRETARY</h2>
                    <button type="button" class="text-blue-500" onclick="resetSelection('secretary')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm mb-4">You may only select 1 candidate.</p>

                <div class="space-y-4">
                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="secretary-1" name="secretary" value="Mikasa Ackerman" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Mikasa Ackerman"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Mikasa Ackerman</h3>
                            <p class="text-sm text-gray-600">BUSORG PARTYLIST</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="secretary-2" name="secretary" value="Umaru Chan" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/women/41.jpg" alt="Umaru Chan"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Umaru Chan</h3>
                            <p class="text-sm text-gray-600">IRI PARTYLIST</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700"
                        onclick="submitVote('secretary')">
                        Submit
                    </button>
                </div>
            </div>

            <!-- Treasurer -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">TREASURER</h2>
                    <button type="button" class="text-blue-500" onclick="resetSelection('treasurer')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm mb-4">You may only select 1 candidate.</p>

                <div class="space-y-4">
                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="treasurer-1" name="treasurer" value="Satoru Gojo" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/62.jpg" alt="Satoru Gojo"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Satoru Gojo</h3>
                            <p class="text-sm text-gray-600">BUSORG PARTYLIST</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="treasurer-2" name="treasurer" value="Lelouch Vi Britannia"
                            class="mr-3">
                        <img src="https://randomuser.me/api/portraits/women/62.jpg" alt="Lelouch Vi Britannia"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Lelouch Vi Britannia</h3>
                            <p class="text-sm text-gray-600">IRI PARTYLIST</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700"
                        onclick="submitVote('treasurer')">
                        Submit
                    </button>
                </div>
            </div>

            <!-- Public Information Officer -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">PUBLIC INFORMATION OFFICER (PIO)</h2>
                    <button type="button" class="text-blue-500" onclick="resetSelection('pio')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm mb-4">You may only select 1 candidate.</p>

                <div class="space-y-4">
                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="pio-1" name="pio" value="Carlos Reyes" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/72.jpg" alt="Carlos Reyes"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Carlos Reyes</h3>
                            <p class="text-sm text-gray-600">BUSORG PARTYLIST</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="pio-2" name="pio" value="Sofia Garcia" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/women/72.jpg" alt="Sofia Garcia"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Sofia Garcia</h3>
                            <p class="text-sm text-gray-600">IRI PARTYLIST</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700"
                        onclick="submitVote('pio')">
                        Submit
                    </button>
                </div>
            </div>

            <!-- Auditor -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">AUDITOR</h2>
                    <button type="button" class="text-blue-500" onclick="resetSelection('auditor')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm mb-4">You may only select 1 candidate.</p>

                <div class="space-y-4">
                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="auditor-1" name="auditor" value="Kakashi Hatake" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/82.jpg" alt="Kakashi Hatake"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Kakashi Hatake</h3>
                            <p class="text-sm text-gray-600">BUSORG PARTYLIST</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="auditor-2" name="auditor" value="Eren Jaeger" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/women/82.jpg" alt="Eren Jaeger"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Eren Jaeger</h3>
                            <p class="text-sm text-gray-600">IRI PARTYLIST</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700"
                        onclick="submitVote('auditor')">
                        Submit
                    </button>
                </div>
            </div>

            <!-- Protocol Officer -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">PROTOCOL OFFICER</h2>
                    <button type="button" class="text-blue-500" onclick="resetSelection('protocol')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm mb-4">You may only select 1 candidate.</p>

                <div class="space-y-4">
                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="protocol-1" name="protocol" value="David Tan" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/men/92.jpg" alt="David Tan"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">David Tan</h3>
                            <p class="text-sm text-gray-600">BUSORG PARTYLIST</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 border rounded-md">
                        <input type="radio" id="protocol-2" name="protocol" value="Isabella Santos" class="mr-3">
                        <img src="https://randomuser.me/api/portraits/women/92.jpg" alt="Isabella Santos"
                            class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h3 class="font-medium">Isabella Santos</h3>
                            <p class="text-sm text-gray-600">IRI PARTYLIST</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-right">
                    <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700"
                        onclick="submitVote('protocol')">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Thank You Modal -->
    <div id="thankYouModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-green-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h3 class="text-xl font-bold mb-2">Thank You!</h3>
                <p id="modalMessage" class="text-gray-600 mb-4">Your vote has been recorded.</p>
                <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-md hover:bg-blue-700 w-full"
                    onclick="closeModal()">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function resetSelection(position) {
            const radioButtons = document.getElementsByName(position);
            radioButtons.forEach(button => {
                button.checked = false;
            });
        }

        function submitVote(position) {
            const radioButtons = document.getElementsByName(position);
            let selectedCandidate = null;

            radioButtons.forEach(button => {
                if (button.checked) {
                    selectedCandidate = button.value;
                }
            });

            if (selectedCandidate) {
                document.getElementById('modalMessage').textContent = `Thank you for voting ${selectedCandidate}!`;
                document.getElementById('thankYouModal').classList.remove('hidden');
                document.getElementById('thankYouModal').classList.add('flex');
            } else {
                alert('Please select a candidate before submitting your vote.');
            }
        }

        function closeModal() {
            document.getElementById('thankYouModal').classList.add('hidden');
            document.getElementById('thankYouModal').classList.remove('flex');
        }
    </script>
@endsection
