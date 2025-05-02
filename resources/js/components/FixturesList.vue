<template>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1
                class="text-xl leading-6 font-medium text-gray-900 text-center mb-6"
            >
                <font-awesome-icon
                    icon="fa-solid fa-list"
                    class="mr-2 text-green-600"
                />
                Generated Fixtures
            </h1>

            <!-- Loading State -->
            <div v-if="loading" class="text-center text-gray-500 py-4">
                <font-awesome-icon
                    icon="fa-solid fa-spinner"
                    class="animate-spin h-5 w-5 text-green-600 mx-auto mb-2"
                />
                Loading fixtures...
            </div>

            <!-- Error State -->
            <div
                v-else-if="error"
                class="mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-md text-sm"
            >
                <p class="font-medium">
                    <font-awesome-icon
                        icon="fa-solid fa-exclamation-triangle"
                        class="mr-1"
                    />
                    Error loading fixtures:
                </p>
                <p class="pl-5">{{ error }}</p>
                <router-link
                    v-if="error.includes('generate')"
                    to="/"
                    class="mt-2 inline-block text-indigo-600 hover:text-indigo-800 underline font-medium"
                >
                    Generate Fixtures Now
                </router-link>
            </div>

            <!-- Content State (Fixtures Found) -->
            <div v-else-if="weeks && weeks.length">
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8"
                >
                    <div
                        v-for="(week, index) in weeks"
                        :key="index"
                        class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm"
                    >
                        <h2
                            class="text-sm font-semibold text-gray-700 text-center mb-3 border-b border-gray-300 pb-2"
                        >
                            Week {{ index + 1 }}
                        </h2>
                        <ul class="space-y-2">
                            <li
                                v-for="match in week"
                                :key="match.id"
                                class="flex justify-between items-center text-xs text-gray-700"
                            >
                                <span
                                    class="text-right w-2/5 truncate font-medium flex items-center justify-end"
                                    :title="match.homeTeam"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-futbol"
                                        class="mr-1 text-gray-400 w-3 h-3 hidden sm:inline-block"
                                    />
                                    {{ match.homeTeamShort }}
                                </span>
                                <span
                                    class="text-gray-400 mx-1 font-bold"
                                    :title="
                                        match.homeTeam + ' vs ' + match.awayTeam
                                    "
                                >
                                    v
                                </span>
                                <span
                                    class="text-left w-2/5 truncate font-medium flex items-center"
                                    :title="match.awayTeam"
                                >
                                    <font-awesome-icon
                                        icon="fa-solid fa-futbol"
                                        class="mr-1 text-gray-400 w-3 h-3 hidden sm:inline-block"
                                    />
                                    {{ match.awayTeamShort }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-center mt-6 border-t border-gray-200 pt-6">
                    <button
                        @click="startSimulation"
                        :disabled="!weeks.length"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:bg-green-300 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    >
                        Start Simulation
                        <font-awesome-icon
                            icon="fa-solid fa-chevron-right"
                            class="ml-3 -mr-1 h-5 w-5"
                        />
                    </button>
                </div>
            </div>

            <!-- Content State (No Fixtures) -->
            <div
                v-else
                class="text-center text-gray-500 mt-6 py-4 italic border-t border-gray-200"
            >
                No fixtures generated yet.
                <router-link
                    to="/"
                    class="block mt-2 text-indigo-600 hover:text-indigo-800 underline font-medium"
                >
                    Generate Fixture
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "FixturesList",
    data() {
        return {
            weeks: [],
            loading: false,
            error: null,
        };
    },
    mounted() {
        this.fetchFixtures();
    },
    methods: {
        fetchFixtures() {
            this.loading = true;
            this.error = null;
            axios
                .get("/api/v1/fixtures") // Assuming /api/fixtures returns grouped weeks
                .then((response) => {
                    // Assuming the API returns data structured as [[match1, match2], [match3, match4], ...]
                    // Or adapt if the structure is different, e.g., { week1: [...], week2: [...] }
                    this.weeks = response.data.data;
                })
                .catch((error) => {
                    console.error("Error fetching fixtures:", error);
                    this.error =
                        "Failed to load fixtures. " +
                        (error.response?.data?.message || error.message);
                    if (error.response?.status === 404) {
                        this.error =
                            "No fixtures found. Please generate them first.";
                    }
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        startSimulation() {
            // Navigate to simulation view
            this.$router.push({ name: "SimulationView" });
        },
    },
};
</script>
