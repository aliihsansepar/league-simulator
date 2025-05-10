<template>
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 text-center mb-8">
            <font-awesome-icon
                icon="fa-solid fa-chart-line"
                class="mr-2 text-blue-600"
            />
            Simulation Dashboard
        </h1>

        <!-- Loading State -->
        <div v-if="loading" class="text-center text-gray-500 py-10">
            <font-awesome-icon
                icon="fa-solid fa-spinner"
                class="animate-spin h-6 w-6 text-blue-600 mx-auto mb-3"
            />
            Loading simulation data...
        </div>

        <!-- Error State -->
        <div
            v-else-if="error"
            class="my-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"
        >
            <p class="font-medium">
                <font-awesome-icon
                    icon="fa-solid fa-exclamation-triangle"
                    class="mr-1"
                />
                Error loading simulation:
            </p>
            <p class="pl-5">{{ error }}</p>
            <router-link
                v-if="error.includes('fixtures')"
                to="/"
                class="mt-2 inline-block text-indigo-600 hover:text-indigo-800 underline font-medium"
            >
                Go to Fixture Generation
            </router-link>
        </div>

        <!-- Content State -->
        <div v-else>
            <!-- Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- League Table -->
                <div
                    class="lg:col-span-2 bg-white shadow-md rounded-lg overflow-hidden"
                >
                    <h2
                        class="text-lg font-semibold text-gray-800 px-4 py-3 sm:px-6 border-b border-gray-200"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-list"
                            class="mr-2 text-gray-400"
                        />
                        League Table
                    </h2>
                    <div v-if="leagueTable.length" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        #
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5 sm:w-auto"
                                    >
                                        Team
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        P
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        W
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        D
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        L
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        GF
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        GA
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        GD
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Pts
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white divide-y divide-gray-200 text-sm"
                            >
                                <tr
                                    v-for="(team, index) in leagueTable"
                                    :key="team.id"
                                    :class="{
                                        'bg-gray-50': index % 2 !== 0,
                                        'hover:bg-blue-50': true,
                                    }"
                                >
                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center text-gray-500"
                                    >
                                        {{ team.position }}
                                    </td>
                                    <td
                                        class="px-4 py-2 whitespace-nowrap font-medium text-gray-900"
                                    >
                                        {{ team.team.name }}
                                    </td>
                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center text-gray-500"
                                    >
                                        {{ team.matches_played }}
                                    </td>
                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center text-gray-500"
                                    >
                                        {{ team.wins }}
                                    </td>
                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center text-gray-500"
                                    >
                                        {{ team.draws }}
                                    </td>
                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center text-gray-500"
                                    >
                                        {{ team.losses }}
                                    </td>

                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center text-gray-500"
                                    >
                                        {{ team.goals_for }}
                                    </td>
                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center text-gray-500"
                                    >
                                        {{ team.goals_against }}
                                    </td>
                                    <td
                                        class="px-1 py-2 whitespace-nowrap text-center font-medium"
                                        :class="
                                            team.goal_difference > 0
                                                ? 'text-green-600'
                                                : team.goal_difference < 0
                                                ? 'text-red-600'
                                                : 'text-gray-500'
                                        "
                                    >
                                        {{ team.goal_difference }}
                                    </td>
                                    <td
                                        class="px-2 py-2 whitespace-nowrap text-center font-bold text-gray-700"
                                    >
                                        {{ team.points }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="p-6 text-center text-gray-500 italic">
                        No league table data available.
                    </div>
                </div>

                <!-- Side Panel -->
                <div class="space-y-6">
                    <!-- Current Week Matches -->
                    <div
                        class="bg-white shadow-md rounded-lg px-4 py-3 sm:px-6"
                    >
                        <h2
                            class="text-base font-semibold text-gray-800 mb-3 border-b border-gray-200 pb-2 flex justify-between items-center"
                        >
                            <div>
                                <font-awesome-icon
                                    icon="fa-solid fa-futbol"
                                    class="mr-2 text-gray-400"
                                />
                                <span v-if="simulationComplete"
                                    >Lig Tamamlandı</span
                                >
                                <span v-else
                                    >Week {{ currentWeek || "-" }} Matches</span
                                >
                            </div>

                            <!-- İlerleme Göstergesi -->
                            <div
                                class="text-xs text-gray-500"
                                v-if="maxWeek > 0"
                            >
                                {{ completionPercentage }}% tamamlandı
                            </div>
                        </h2>

                        <!-- İlerleme Çubuğu -->
                        <div
                            v-if="maxWeek > 0"
                            class="w-full bg-gray-200 rounded-full h-2 mb-3"
                        >
                            <div
                                class="h-2 rounded-full"
                                :class="
                                    isLeagueComplete
                                        ? 'bg-green-500'
                                        : 'bg-blue-500'
                                "
                                :style="{ width: completionPercentage + '%' }"
                            ></div>
                        </div>

                        <!-- Lig Tamamlandı Uyarısı -->
                        <div
                            v-if="simulationComplete"
                            class="bg-green-50 border border-green-200 rounded-md p-3 mb-3 flex items-center"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-trophy"
                                class="mr-2 text-yellow-500 text-lg"
                            />
                            <div>
                                <div class="font-semibold text-green-800">
                                    Congratulations!
                                </div>
                                <div class="text-sm text-green-700">
                                    {{ maxWeek }} week league season completed.
                                    Champion:
                                    <span class="font-bold">{{
                                        leagueTable.length > 0
                                            ? leagueTable[0].team.name
                                            : ""
                                    }}</span>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="!simulationComplete && currentMatches.length"
                            class="space-y-1 text-xs"
                        >
                            <div
                                v-for="match in currentMatches"
                                :key="match.id"
                                class="p-2 bg-gray-50 rounded-lg mb-2 shadow-sm hover:shadow-md transition-all"
                            >
                                <div class="flex justify-between items-center">
                                    <!-- Home Team -->
                                    <div class="w-5/12 text-right">
                                        <span
                                            class="font-medium text-gray-800 truncate"
                                            >{{
                                                match.home_team.short_name
                                            }}</span
                                        >
                                    </div>

                                    <!-- Score Box -->
                                    <div
                                        class="flex items-center justify-center w-2/12 bg-white rounded-md shadow-inner px-2 py-1"
                                    >
                                        <span
                                            v-if="
                                                match.match &&
                                                (match.match.home_score !==
                                                    undefined ||
                                                    match.match.away_score !==
                                                        undefined)
                                            "
                                            class="font-bold text-sm text-green-600"
                                            >{{ match.match.home_score }}</span
                                        >
                                        <span
                                            v-if="
                                                match.match &&
                                                (match.match.home_score !==
                                                    undefined ||
                                                    match.match.away_score !==
                                                        undefined)
                                            "
                                            class="text-gray-400 font-bold mx-1"
                                            >-</span
                                        >
                                        <span
                                            v-if="
                                                match.match &&
                                                (match.match.home_score !==
                                                    undefined ||
                                                    match.match.away_score !==
                                                        undefined)
                                            "
                                            class="font-bold text-sm text-green-600"
                                            >{{ match.match.away_score }}</span
                                        >
                                        <span
                                            v-else
                                            class="text-xs text-gray-500"
                                            >vs</span
                                        >
                                    </div>

                                    <!-- Away Team -->
                                    <div class="w-5/12 text-left">
                                        <span
                                            class="font-medium text-gray-800 truncate"
                                            >{{
                                                match.away_team.short_name
                                            }}</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            v-else-if="simulationComplete"
                            class="text-center text-green-600 font-medium py-3 text-sm"
                        >
                            Final standings generated.
                        </div>
                        <div
                            v-else
                            class="text-center text-gray-500 italic py-3 text-sm"
                        >
                            No matches scheduled.
                        </div>

                        <!-- Previous Week Matches -->
                        <div
                            v-if="
                                previousMatches.length &&
                                currentWeek > 1 &&
                                !simulationComplete
                            "
                        >
                            <h3
                                class="text-sm font-medium text-gray-600 mt-4 mb-2 border-t border-gray-100 pt-3"
                            >
                                <font-awesome-icon
                                    icon="fa-solid fa-history"
                                    class="mr-1 text-gray-400"
                                />
                                Week {{ currentWeek - 1 }} Results
                            </h3>
                            <div class="space-y-1 text-xs">
                                <div
                                    v-for="match in previousMatches"
                                    :key="'prev-' + match.id"
                                    class="p-2 bg-gray-50 rounded-lg mb-2 shadow-sm opacity-80"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <!-- Home Team -->
                                        <div class="w-5/12 text-right">
                                            <span
                                                class="font-medium text-gray-700 truncate"
                                                >{{
                                                    match.home_team.short_name
                                                }}</span
                                            >
                                        </div>

                                        <!-- Score Box -->
                                        <div
                                            class="flex items-center justify-center w-2/12 bg-white rounded-md shadow-inner px-2 py-1"
                                        >
                                            <span
                                                v-if="
                                                    match.match &&
                                                    match.match.home_score !==
                                                        undefined
                                                "
                                                :class="[
                                                    'font-bold text-sm',
                                                    match.match.home_score >
                                                    match.match.away_score
                                                        ? 'text-green-600'
                                                        : match.match
                                                              .home_score <
                                                          match.match.away_score
                                                        ? 'text-gray-500'
                                                        : 'text-blue-600',
                                                ]"
                                                >{{
                                                    match.match.home_score
                                                }}</span
                                            >
                                            <span
                                                class="text-gray-400 font-bold mx-1"
                                                >-</span
                                            >
                                            <span
                                                v-if="
                                                    match.match &&
                                                    match.match.away_score !==
                                                        undefined
                                                "
                                                :class="[
                                                    'font-bold text-sm',
                                                    match.match.away_score >
                                                    match.match.home_score
                                                        ? 'text-green-600'
                                                        : match.match
                                                              .away_score <
                                                          match.match.home_score
                                                        ? 'text-gray-500'
                                                        : 'text-blue-600',
                                                ]"
                                                >{{
                                                    match.match.away_score
                                                }}</span
                                            >
                                        </div>

                                        <!-- Away Team -->
                                        <div class="w-5/12 text-left">
                                            <span
                                                class="font-medium text-gray-700 truncate"
                                                >{{
                                                    match.away_team.short_name
                                                }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Championship Predictions -->
                    <div
                        class="bg-white shadow-md rounded-lg px-4 py-3 sm:px-6"
                    >
                        <h2
                            class="text-base font-semibold text-gray-800 mb-3 border-b border-gray-200 pb-2"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-chart-line"
                                class="mr-2 text-gray-400"
                            />
                            Championship Predictions
                        </h2>
                        <div
                            v-if="predictions.length"
                            class="overflow-x-auto text-sm"
                        >
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody
                                    class="bg-white divide-y divide-gray-100"
                                >
                                    <tr
                                        v-for="prediction in predictions.slice(
                                            0,
                                            5
                                        )"
                                        :key="prediction.team_name"
                                    >
                                        <!-- Show top 5 -->
                                        <td
                                            class="pl-2 pr-3 py-1.5 whitespace-nowrap font-medium text-gray-800"
                                        >
                                            {{ prediction.team_name }}
                                        </td>
                                        <td
                                            class="px-2 py-1.5 whitespace-nowrap text-gray-500 text-right"
                                        >
                                            {{
                                                prediction.percentage.toFixed(
                                                    1
                                                )
                                            }}%
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            v-else
                            class="text-center text-gray-500 italic py-3 text-sm"
                        >
                            No prediction data available.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Simulation Controls -->
            <div
                class="bg-white shadow-md rounded-lg p-4 mt-6 flex flex-col sm:flex-row justify-center items-center space-y-3 sm:space-y-0 sm:space-x-4"
            >
                <button
                    @click="playNextWeek"
                    :disabled="actionLoading || simulationComplete"
                    :class="[
                        'w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out',
                        simulationComplete || actionLoading
                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                            : 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
                    ]"
                >
                    <font-awesome-icon
                        :icon="
                            actionLoading && currentAction === 'playNext'
                                ? 'fa-solid fa-spinner'
                                : 'fa-solid fa-play'
                        "
                        :class="{
                            'animate-spin':
                                actionLoading && currentAction === 'playNext',
                        }"
                        class="-ml-1 mr-2 h-4 w-4"
                    />
                    <span v-if="simulationComplete"> Lig Tamamlandı </span>
                    <span v-else-if="isLeagueComplete"> Son Hafta </span>
                    <span v-else> Play Week {{ currentWeek }} </span>
                </button>
                <button
                    @click="playAllWeeks"
                    :disabled="actionLoading || simulationComplete"
                    :class="[
                        'w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out',
                        simulationComplete || actionLoading
                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                            : 'text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500',
                    ]"
                >
                    <font-awesome-icon
                        :icon="
                            actionLoading && currentAction === 'playAll'
                                ? 'fa-solid fa-spinner'
                                : 'fa-solid fa-forward-step'
                        "
                        :class="{
                            'animate-spin':
                                actionLoading && currentAction === 'playAll',
                        }"
                        class="-ml-1 mr-2 h-4 w-4"
                    />
                    <span v-if="simulationComplete"> Lig Tamamlandı </span>
                    <span v-else> Play All Weeks </span>
                </button>
                <button
                    @click="resetData"
                    :disabled="actionLoading"
                    :class="[
                        'w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out',
                        actionLoading
                            ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                            : 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500',
                    ]"
                >
                    <font-awesome-icon
                        :icon="
                            actionLoading && currentAction === 'reset'
                                ? 'fa-solid fa-spinner'
                                : 'fa-solid fa-redo'
                        "
                        :class="{
                            'animate-spin':
                                actionLoading && currentAction === 'reset',
                        }"
                        class="-ml-1 mr-2 h-4 w-4"
                    />
                    Reset Simulation
                </button>
            </div>
            <!-- Action Error Message -->
            <div
                v-if="actionError"
                class="mt-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-md text-sm"
            >
                <p class="font-medium">
                    <font-awesome-icon
                        icon="fa-solid fa-exclamation-triangle"
                        class="mr-1"
                    />
                    Action Failed:
                </p>
                <p class="pl-5">{{ actionError }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "SimulationView",
    data() {
        return {
            leagueTable: [],
            currentMatches: [],
            previousMatches: [], // Önceki hafta maçları için yeni dizi
            predictions: [],
            currentWeek: parseInt(localStorage.getItem("currentWeek")) || 1, // LocalStorage'dan al veya 1 olarak başla
            simulationComplete: false,
            maxWeek: 0, // Hesaplanacak maksimum hafta sayısı
            loading: false,
            error: null, // Initial load error
            actionLoading: false, // Button action loading
            actionError: null, // Button action error
            currentAction: null, // Tracks which button was clicked
        };
    },
    computed: {
        isLeagueComplete() {
            return this.currentWeek > this.maxWeek;
        },
        completionPercentage() {
            if (this.maxWeek === 0 || this.currentWeek === 1) return 0;

            return Math.min(
                Math.round((this.currentWeek / this.maxWeek) * 100),
                100
            );
        },
    },
    mounted() {
        this.fetchStandings();
        this.fetchFixtures();
        this.fetchPreviousFixtures();
        this.fetchPredictions();
    },
    watch: {
        // currentWeek değiştiğinde localStorage'a kaydet
        currentWeek(newValue) {
            localStorage.setItem("currentWeek", newValue);
        },
    },
    methods: {
        fetchSimulationState() {
            this.loading = true;
            this.error = null;
            axios
                .get("/api/v1/matches/simulate") // Endpoint to get current state
                .then((response) => {
                    this.updateState(response.data);
                })
                .catch((error) => {
                    console.error("Error fetching simulation state:", error);
                    this.error =
                        "Failed to load simulation state. " +
                        (error.response?.data?.message || error.message);
                    if (error.response?.status === 404) {
                        this.error =
                            "Simulation data not found. Please ensure fixtures are generated.";
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        playAllWeeks() {
            this.currentWeek = 6;
            localStorage.setItem("currentWeek", this.currentWeek); // LocalStorage'ı güncelle

            this.runSimulationAction(
                "playAll",
                "/api/v1/matches/simulate-season"
            );

            // Önceki hafta verilerini güncelle
            this.fetchPreviousFixtures();
        },
        playNextWeek() {
            const weekToPlay = this.currentWeek;
            // Increment current week immediately for UI update
            this.currentWeek++;
            localStorage.setItem("currentWeek", this.currentWeek); // LocalStorage'ı güncelle

            // Önceki hafta verilerini güncelle
            this.fetchPreviousFixtures();

            this.runSimulationAction(
                "playNext",
                "/api/v1/matches/simulate-week",
                { week: weekToPlay }
            );
        },
        async resetData() {
            try {
                this.actionLoading = true;
                this.actionError = null;
                this.currentAction = "reset";

                const response = await axios.post("/api/v1/matches/reset");

                // Simülasyon reset işlemi başarılı olduktan sonra week değerini 1'e sıfırla
                this.currentWeek = 1;
                localStorage.setItem("currentWeek", 1);

                // Yeni verileri çek
                await this.fetchStandings();
                await this.fetchFixtures();
                await this.fetchPreviousFixtures();
                await this.fetchPredictions();

                // Yeniden kontrol et (kesin olarak 1 olmasını sağla)
                this.currentWeek = 1;
                localStorage.setItem("currentWeek", 1);

                this.actionLoading = false;
                this.currentAction = null;
                this.simulationComplete = false;

                console.log("Simulation reset completed. Week set to 1.");
            } catch (error) {
                console.error("Reset failed:", error);
                this.actionError =
                    "Failed to reset. " +
                    (error.response?.data?.message || error.message);
                this.actionLoading = false;
                this.currentAction = null;
            }
        },
        runSimulationAction(actionName, apiUrl, params = {}) {
            this.actionLoading = true;
            this.actionError = null;
            this.currentAction = actionName;

            return new Promise((resolve, reject) => {
                axios
                    .post(apiUrl, params)
                    .then((response) => {
                        // Reset durumunda updateState fonksiyonunu çağırmayalım
                        if (actionName === "reset") {
                            console.log("Simulation reset.");
                            // resetData fonksiyonu zaten tüm işlemleri yapıyor
                            resolve(response.data);
                            return;
                        }

                        // Diğer durumlar için normal akışa devam et
                        this.updateState(response.data);
                        resolve(response.data);
                    })
                    .catch((error) => {
                        console.error(`Error during ${actionName}:`, error);
                        this.actionError =
                            `Failed to ${actionName
                                .replace(/([A-Z])/g, " $1")
                                .toLowerCase()}. ` +
                            (error.response?.data?.message || error.message);
                        reject(error);
                    })
                    .finally(() => {
                        if (actionName !== "reset") {
                            // Reset durumunda resetData zaten bu işlemleri yapıyor
                            this.actionLoading = false;
                            this.currentAction = null;
                        }
                    });
            });
        },
        updateState(data) {
            // Update component data based on API response
            // Adjust keys based on your actual API response structure
            this.leagueTable = data.league_table || this.fetchStandings();
            this.currentMatches = data.current_matches || this.fetchFixtures();
            this.previousMatches =
                data.previous_matches || this.fetchPreviousFixtures();
            this.predictions = data.predictions || this.fetchPredictions();

            // Maksimum hafta sayısını hesapla
            if (this.leagueTable && this.leagueTable.length > 0) {
                const teamCount = this.leagueTable.length;
                this.maxWeek = (teamCount - 1) * 2;
                console.log(
                    `Takım sayısı: ${teamCount}, Max hafta: ${this.maxWeek}`
                );

                // Lig tamamlandı mı kontrol et
                if (this.currentWeek > this.maxWeek) {
                    this.simulationComplete = true;
                }
            }

            // currentWeek değerini API'den alırken dikkatli olalım
            // Eğer "reset" yapıldıysa, varsayılan değer 1 olmalı
            if (data.current_week && this.currentAction !== "reset") {
                this.currentWeek = data.current_week;
                localStorage.setItem("currentWeek", data.current_week);
            }

            // Eğer currentWeek maxWeek'e ulaştıysa simülasyon tamamlanmış olarak işaretleyelim
            if (this.currentWeek > this.maxWeek && this.maxWeek > 0) {
                this.simulationComplete = true;
            } else {
                // API'den gelen değeri kullan
                this.simulationComplete = data.simulation_complete || false;
            }

            // If simulation is complete, ensure currentWeek reflects total weeks
            if (this.simulationComplete && data.total_weeks) {
                this.currentWeek = data.total_weeks;
                localStorage.setItem("currentWeek", data.total_weeks);
            } else if (this.simulationComplete && this.maxWeek > 0) {
                // Eğer simulasyon tamamlandıysa ve maxWeek bilgisi varsa, currentWeek'i maxWeek olarak ayarla
                this.currentWeek = this.maxWeek;
                localStorage.setItem("currentWeek", this.maxWeek);
            }

            // Assuming simulation actions might return predictions in the same format or an array
            const preds = data.predictions || this.fetchPredictions();
            if (Array.isArray(preds)) {
                this.predictions = preds;
            } else if (typeof preds === "object" && preds !== null) {
                // If simulation actions return object format, convert it
                this.predictions = Object.values(preds);
            } else {
                this.predictions = [];
            }

            // Ensure points are calculated (assuming API doesn't provide them directly)
            this.leagueTable.forEach((t) => (t.points = t.wins * 3 + t.draws)); // Adjusted property names based on screenshot

            // Sort tables
            this.leagueTable.sort(
                (a, b) =>
                    b.points - a.points ||
                    b.goal_difference - a.goal_difference ||
                    a.team.name.localeCompare(b.team.name) // Adjusted property access
            );

            // Sort predictions
            this.predictions.sort((a, b) => b.percentage - a.percentage);
        },
        fetchStandings() {
            axios.get("/api/v1/standings").then((response) => {
                this.leagueTable = response.data;

                // Maksimum hafta sayısını hesapla
                if (this.leagueTable && this.leagueTable.length > 0) {
                    const teamCount = this.leagueTable.length;
                    this.maxWeek = (teamCount - 1) * 2;
                    console.log(
                        `Takım sayısı: ${teamCount}, Max hafta: ${this.maxWeek}`
                    );

                    // Lig tamamlandı mı kontrol et
                    if (this.currentWeek > this.maxWeek) {
                        this.simulationComplete = true;
                    }
                }
            });
        },
        fetchPredictions() {
            axios
                .get("/api/v1/fixtures/predictions")
                .then((response) => {
                    // Convert the incoming object to an array of its values
                    const predictionsObject = response.data.data;
                    if (predictionsObject) {
                        this.predictions = Object.values(predictionsObject);
                        // Sort predictions after converting to array
                        this.predictions.sort(
                            (a, b) => b.percentage - a.percentage
                        );
                        console.log("Formatted Predictions:", this.predictions); // Log the formatted array
                    } else {
                        this.predictions = [];
                        console.warn(
                            "Predictions data received was empty or invalid."
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error fetching predictions:", error);
                    this.predictions = []; // Clear predictions on error
                    // Optionally show a global alert for prediction fetch error
                    // emitter.emit('show-alert', { type: 'error', message: 'Failed to load predictions.' });
                });
        },
        fetchFixtures() {
            axios
                .get("/api/v1/fixtures/" + this.currentWeek)
                .then((response) => {
                    this.currentMatches = response.data.data;
                });
        },
        fetchPreviousFixtures() {
            // Eğer ilk haftaysak önceki hafta yoktur
            if (this.currentWeek <= 1) {
                this.previousMatches = [];
                return;
            }

            axios
                .get("/api/v1/fixtures/" + (this.currentWeek - 1))
                .then((response) => {
                    if (response.data && response.data.data) {
                        this.previousMatches = response.data.data;
                    } else {
                        this.previousMatches = [];
                    }
                })
                .catch((error) => {
                    console.error("Error fetching previous fixtures:", error);
                    this.previousMatches = [];
                });
        },
    },
};
</script>
