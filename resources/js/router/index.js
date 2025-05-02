import { createRouter, createWebHistory } from "vue-router";
import GenerateFixture from "../components/GenerateFixture.vue";
import FixturesList from "../components/FixturesList.vue";
import SimulationView from "../components/SimulationView.vue";

const routes = [
    {
        path: "/",
        name: "GenerateFixture",
        component: GenerateFixture,
    },
    {
        path: "/fixtures",
        name: "FixturesList",
        component: FixturesList,
    },
    {
        path: "/simulation",
        name: "SimulationView",
        component: SimulationView,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
