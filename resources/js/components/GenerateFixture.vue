<template>
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1
                class="text-xl leading-6 font-medium text-gray-900 text-center mb-6"
            >
                <font-awesome-icon
                    icon="fa-solid fa-list"
                    class="mr-2 text-indigo-600"
                />
                Generate Fixture
            </h1>

            <!-- Loading State -->
            <div v-if="loadingTeams" class="text-center text-gray-500 py-4">
                <font-awesome-icon
                    icon="fa-solid fa-spinner"
                    class="animate-spin h-5 w-5 text-indigo-600 mx-auto mb-2"
                />
                Loading teams...
            </div>

            <!-- Content State (Teams Found) -->
            <div v-if="!loadingTeams && teams.length" class="mt-4">
                <h2 class="text-base font-semibold text-gray-600 mb-3">
                    Available Teams ({{ teams.length }})
                </h2>
                <div
                    class="max-h-60 overflow-y-auto border rounded-md p-3 bg-gray-50 mb-6"
                >
                    <ul class="space-y-2 text-gray-800 text-sm">
                        <li
                            v-for="team in teams"
                            :key="team.id"
                            class="pl-2 flex items-center"
                        >
                            <font-awesome-icon
                                icon="fa-solid fa-futbol"
                                class="mr-2 text-gray-400 w-3"
                            />
                            {{ team.name }}
                        </li>
                    </ul>
                </div>
                <div class="text-center flex flex-col sm:flex-row gap-2">
                    <!-- Center the button -->
                    <button
                        @click="generateFixture"
                        :disabled="loadingGenerate || !teams.length"
                        class="w-full sm:w-auto inline-flex items-center justify-center py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:bg-indigo-300 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                    >
                        <font-awesome-icon
                            v-if="loadingGenerate"
                            icon="fa-solid fa-spinner"
                            class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                        />
                        <font-awesome-icon
                            v-else="loadingGenerate"
                            icon="fa-bolt"
                            class="ml-1 mr-3 h-5 w-5 text-white"
                        />
                        {{
                            loadingGenerate
                                ? "Generating..."
                                : "Generate Fixture"
                        }}
                    </button>
                    <button
                        class="w-full sm:w-auto inline-flex items-center justify-center py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:bg-indigo-300 disabled:cursor-not-allowed transition duration-150 ease-in-out"
                        @click="resetFixture"
                    >
                        <font-awesome-icon
                            icon="fa-solid fa-rotate-left"
                            class="mr-2 text-gray-400 w-3"
                        />
                        Reset Fixture
                    </button>
                </div>
                <div
                    v-if="loadingGenerate"
                    class="text-center text-gray-500 py-4"
                >
                    <font-awesome-icon
                        icon="fa-solid fa-spinner"
                        class="animate-spin h-5 w-5 text-indigo-600 mx-auto mb-2"
                    />
                    Generating fixture...
                </div>
            </div>

            <!-- Content State (No Teams Found) -->
            <div
                v-if="!loadingTeams && !teams.length"
                class="text-center text-gray-500 mt-6 py-4 italic border-t border-gray-200"
            >
                No teams found in the database. Please add teams via
                backend/seeder first.
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import emitter from "../eventBus"; // Import event bus

export default {
    name: "GenerateFixture",
    data() {
        return {
            teams: [],
            loadingTeams: false,
            loadingGenerate: false,
        };
    },
    mounted() {
        this.fetchTeams();
    },
    methods: {
        fetchTeams() {
            this.loadingTeams = true;
            axios
                .get("/api/v1/teams") // Assuming /api/teams endpoint exists
                .then((response) => {
                    this.teams = response.data;
                })
                .catch((error) => {
                    console.error("Error fetching teams:", error);
                    emitter.emit("show-alert", {
                        type: "error",
                        message: "Failed to load teams.",
                    });
                })
                .finally(() => {
                    this.loadingTeams = false;
                });
        },
        resetFixture() {
            this.loadingGenerate = true;
            emitter.emit("show-alert", {
                type: "info",
                message: "Resetting fixtures...",
            });
            axios
                .post("/api/v1/fixtures/reset")
                .then((response) => {
                    console.log("Fixture reset successfully:", response.data);
                    emitter.emit("show-alert", {
                        type: "success",
                        message: "Fixtures reset successfully.",
                    });
                    this.fetchTeams();
                })
                .catch((error) => {
                    console.error("Error resetting fixtures:", error);
                    const message =
                        "Failed to reset fixtures. " +
                        (error.response?.data?.message || error.message);
                    emitter.emit("show-alert", {
                        type: "error",
                        message: message,
                    });
                })
                .finally(() => {
                    this.loadingGenerate = false;
                });
        },
        generateFixture() {
            this.loadingGenerate = true;
            emitter.emit("show-alert", {
                type: "info",
                message: "Generating fixture...",
            }); // Show info alert
            axios
                .post("/api/v1/fixtures/generate") // Assuming /api/fixtures/generate endpoint exists
                .then((response) => {
                    console.log(
                        "Fixture generated successfully:",
                        response.data
                    );
                    emitter.emit("show-alert", {
                        type: "success",
                        message: "Fixtures generated successfully!",
                    }); // Show success alert
                    this.$router.push({ name: "FixturesList" });
                })
                .catch((error) => {
                    console.error("Error generating fixture:", error);
                    const message =
                        "Failed to generate fixture. " +
                        (error.response?.data?.message || error.message);
                    emitter.emit("show-alert", {
                        type: "error",
                        message: message,
                    });
                })
                .finally(() => {
                    this.loadingGenerate = false;
                });
        },
    },
};
</script>
