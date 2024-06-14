<template>
  <Card>
    <template #title>{{ companyName.name }}</template>
    <template #content>
      <div class="card">
        <DataTable :value="addArea" tableStyle="min-width:50rem">
          <Column field="name" header="Área"></Column>
          <Column field="max" header="Capacidade t/h"></Column>
          <Column field="med" header="Média t/h"></Column>
          <Column field="min" header="Mínima t/h"></Column>
          <Column field="visible" header="Visibilidade"></Column>
          <Column field="options" header="Opções">
            <template #body="{ data }">
              <Button label="Configurar" @click="configureArea(data)" />
            </template>
          </Column>
        </DataTable>
      </div>
    </template>
  </Card>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useCompanyStore } from "../../store/CompanyStore";

const router = useRouter();
const route = useRoute();
const { getCompanyData } = useCompanyStore();
const addArea = ref([]);
const companyName = ref({});

const configureArea = (area) => {
  router.push({ name: "Config-Áreas", params: { areaId: area.id } });
};

const fetchCompanyData = async () => {
  try {
    const companyData = await getCompanyData;

    if (companyData && companyData.areas) {
      addArea.value = companyData.areas.map((area) => {
        const accumulatedValue = parseFloat(area.accumulated.value);
        const maxCapacity = parseFloat(area.timers?.max_capacity || "0%");
        const minCapacity = parseFloat(area.timers?.min_capacity || "0%");
        const medCapacity = parseFloat(area.timers?.med_capacity || "0%");

        const min = ((accumulatedValue * minCapacity) / 100).toFixed(2);
        const med = ((accumulatedValue * medCapacity) / 100).toFixed(2);
        const max = ((accumulatedValue * maxCapacity) / 100).toFixed(2);

        return {
          id: area.id,
          name: area.name,
          min: min + " t",
          med: med + " t",
          max: max + " t",
          visible: area.visible ? "Sim" : "Não",
        };
      });
    }

    if (companyData && companyData.company) {
      companyName.value = {
        name: companyData.company.name + " / " + route.name,
      };
    }
  } catch (error) {
    console.error("Error fetching company data:", error);
  }
};
onMounted(() => {
  fetchCompanyData();
});
</script>
