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
                            class="text-base font-semibold text-gray-800 mb-3 border-b border-gray-200 pb-2"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-futbol"
                                class="mr-2 text-gray-400"
                            />
                            <span v-if="simulationComplete"
                                >Simulation Complete</span
                            >
                            <span v-else
                                >Week {{ currentWeek || "-" }} Matches</span
                            >
                        </h2>
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
                    Play Week {{ currentWeek }}
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
                    Play All Weeks
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
            predictions: [],
            currentWeek: 1, // Default to week 1
            simulationComplete: false,
            loading: false,
            error: null, // Initial load error
            actionLoading: false, // Button action loading
            actionError: null, // Button action error
            currentAction: null, // Tracks which button was clicked
        };
    },
    mounted() {
        this.fetchStandings();
        this.fetchFixtures();
        this.fetchPredictions();
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
            this.runSimulationAction(
                "playAll",
                "/api/v1/matches/simulate-season"
            );
        },
        playNextWeek() {
            const weekToPlay = this.currentWeek;
            // Increment current week immediately for UI update
            this.currentWeek++;

            this.runSimulationAction(
                "playNext",
                "/api/v1/matches/simulate-week",
                { week: weekToPlay }
            );
        },
        async resetData() {
            try {
                await this.runSimulationAction(
                    "reset",
                    "/api/v1/matches/reset"
                );

                // Reset currentWeek to 1 after simulation reset
                this.currentWeek = 1;

                await this.fetchStandings();
                await this.fetchPredictions();
            } catch (error) {
                console.error("Reset failed:", error);
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
                        this.updateState(response.data);
                        if (actionName === "reset") {
                            console.log("Simulation reset.");
                            this.currentWeek = 1;
                        } else if (actionName === "playNext") {
                            // Increment week after successful simulation
                            this.currentWeek++;
                        }
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
                        this.actionLoading = false;
                        this.currentAction = null;
                    });
            });
        },
        updateState(data) {
            // Update component data based on API response
            // Adjust keys based on your actual API response structure
            this.leagueTable = data.league_table || this.fetchStandings();
            this.currentMatches = data.current_matches || this.fetchFixtures();
            this.predictions = data.predictions || this.fetchPredictions();

            // Update current week from API if provided
            if (data.current_week) {
                this.currentWeek = data.current_week;
            }

            this.simulationComplete = data.simulation_complete || false;

            // If simulation is complete, ensure currentWeek reflects total weeks
            if (this.simulationComplete && data.total_weeks) {
                this.currentWeek = data.total_weeks;
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
            // Sort predictions (already done in fetchPredictions if fetched separately)
            this.predictions.sort((a, b) => b.percentage - a.percentage);
        },
        fetchStandings() {
            axios.get("/api/v1/standings").then((response) => {
                this.leagueTable = response.data;
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
    },
};
</script>
