<template>
    <div class="dashboard p-6">
        <div class="my-6 flex justify-between">
            <div class="flex flex-col">
                <h1 class="text-2xl dark:text-white text-gray-700">Good {{ partOfDay }}, {{ auth.name }}!</h1>
                <p class="text-gray-400 dark:text-gray-500">Here's what's happening with your store today.</p>
            </div>
            <div>
                <VueDatePicker
                    range
                    :dark="theme === 'dark'"
                    :enable-time-picker="false"
                    :clearable="false"
                    :max-date="new Date(Date.now()).toISOString()"
                    :model-value="range"
                    @update:model-value="updateRange"
                    class="edit-component component search-field"
                ></VueDatePicker>
            </div>
        </div>

        <div class="grid grid-cols-6 gap-4 max-[1800px]:grid-cols-3 max-[1080px]:grid-cols-2 max-[580px]:grid-cols-1 mb-4">
            <div
                class="item type-total"
                v-for="card in totalCards"
            >
                <exx-total-card-dropdown
                    v-if="!card.id"
                    @saveCard="saveCard"
                    :type="card.type"
                    :position="card.position"
                    :errors="errors"
                />
                <exx-total-card
                    v-else
                    :response="card.response"
                    :class="`exx-${card.type}-card`"
                    @deleteCard="deleteCard(card.id)"
                />
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 max-[1800px]:grid-cols-1 mb-4">
            <div
                class="item type-horizontal-chart"
                v-for="card in horizontalChartCards"
            >
                <exx-horizontal-chart-card-dropdown
                    v-if="!card.id"
                    @saveCard="saveCard"
                    :type="card.type"
                    :position="card.position"
                    :errors="errors"
                />
                <component
                    v-else
                    :response="card.response"
                    :is="card.chart"
                    :class="card.chart"
                    @deleteCard="deleteCard(card.id)"
                />
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 max-[1800px]:grid-cols-1">
            <div
                class="item type-pie-chart"
                v-for="card in pieCards"
            >
                <exx-pie-chart-card-dropdown
                    v-if="!card.id"
                    @saveCard="saveCard"
                    :type="card.type"
                    :position="card.position"
                    :errors="errors"
                />
                <component
                    v-else
                    :response="card.response"
                    :is="card.chart"
                    :class="card.chart"
                    @deleteCard="deleteCard(card.id)"
                />
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue';
import Base from "@/mixins/Base.js";
import {AgCharts} from "ag-charts-vue3";

export default {
    components: {AgCharts},
    mixins: [Base],
    layout: Layout,
    props: {
        currentRange: {
            type: Array,
            required: true
        },
        currentData: {
            type: Array,
            required: true
        }
    },
    created() {
        this.range = this.currentRange
        this.data = JSON.parse(JSON.stringify(this.currentData))
    },
    methods: {
        saveCard(e) {
            this.errors = {}
            this.$axios.post(this.getAdminUrl('dashboard'), {
                title: e?.title ?? null,
                model: e?.model ?? null,
                chart: e?.chart ?? null,
                value: e?.value ?? null,
                column: e?.column ?? null,
                column_value: e?.columnValue ?? null,
                x_axis: e?.x_axis ?? null,
                by_value: e?.by_value ?? null,
                y_axis: e?.y_axis ?? null,
                relation: e?.relation ?? null,
                y_axis_key: e?.y_axis_key ?? null,
                relation_key: e?.relation_key ?? null,
                type: e?.type ?? null,
                position: e?.position ?? null,
                range: this.range,
            }).then((response) => {
                this.data = response.data
            }).catch((error) => {
                this.errors = {
                    [`${e.type}_${e.position}`]: error.response.data.errors
                }
            })
        },
        deleteCard(id) {
            this.$axios.delete(this.getAdminUrl(`dashboard/${id}`)).then((response) => {
                this.data = response.data
            })
        },
        ensurePositions(array, type, required) {
            required.forEach(pos => {
                if (!array.some(item => item.position === pos)) {
                    array.push({
                        id: null,
                        type: type,
                        position: pos
                    });
                }
            });
            return array.sort((a, b) => a.position - b.position);
        },
        updateRange(e) {
            this.range = [
                new Date(e[0]).toISOString(),
                new Date(e[1]).toISOString()
            ]
            this.$axios.post(this.getAdminUrl('dashboard/range'), {
                range: this.range
            }).then((response) => {
                this.data = response.data
                this.$router.visit(window.location.pathname)
            })
        }
    },
    data() {
        return {
            range: null,
            data: [],
            errors: {}
        }
    },
    computed: {
        totalCards() {
            return this.ensurePositions(
                this.data.filter(c => c.type === 'total'),
                'total',
                [1, 2, 3, 4, 5, 6]
            )
        },
        horizontalChartCards() {
            return this.ensurePositions(
                this.data.filter(c => c.type === 'horizontal'),
                'horizontal',
                [1, 2, 3]
            )
        },
        pieCards() {
            return this.ensurePositions(
                this.data.filter(c => c.type === 'pie'),
                'pie',
                [1, 2, 3]
            )
        },
        partOfDay() {
            const hour = new Date().getHours();
            if (hour >= 5 && hour < 12) {
                return "Morning";
            } else if (hour >= 12 && hour < 17) {
                return "Afternoon";
            } else if (hour >= 17 && hour < 21) {
                return "Evening";
            } else {
                return "Night";
            }
        }
    }
}
</script>
