<template>
    <div id="app" class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Navigation -->
            <nav class="bg-white shadow-sm rounded-lg mb-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-center h-16">
                        <div class="flex space-x-6 md:space-x-10">
                            <!-- Navigation Links -->
                            <router-link
                                to="/"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                active-class="border-indigo-500 text-gray-900"
                                :class="
                                    $route.name === 'GenerateFixture'
                                        ? 'border-indigo-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                "
                            >
                                Generate Fixture
                            </router-link>
                            <router-link
                                to="/fixtures"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                active-class="border-indigo-500 text-gray-900"
                                :class="
                                    $route.name === 'FixturesList'
                                        ? 'border-indigo-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                "
                            >
                                Fixtures
                            </router-link>
                            <router-link
                                to="/simulation"
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                active-class="border-indigo-500 text-gray-900"
                                :class="
                                    $route.name === 'SimulationView'
                                        ? 'border-indigo-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                "
                            >
                                Simulation
                            </router-link>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main>
                <router-view />
            </main>

            <!-- Global Alert Component -->
            <GlobalAlert />
        </div>
    </div>
</template>

<script>
import GlobalAlert from "./components/GlobalAlert.vue"; // Import the component

export default {
    name: "App",
    components: {
        GlobalAlert, // Register the component
    },
};
</script>
