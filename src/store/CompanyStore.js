import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { setStorage, setJsonStorage, delStorage, getStorage, getJsonStorage } from "@/service/Storage";

const useCompanyStore = defineStore('company', () => {
    const companies = ref(getJsonStorage('companies'));
    const company = ref(getJsonStorage('companye'));
    const associates = ref(getJsonStorage('associate'));


    function setCompanies(data){
        setJsonStorage('companies', data);
    }

    function setCompany(data){
        setJsonStorage('company', data);
    }

    function setAssociates(data){
        setJsonStorage('associate', data);
    }

    const getCompanies = computed(() => { 
        return companies.value
    });

    const getCompany = computed(() => { 
        return company.value
    });

    const getAssociates = computed(() => { 
        return associates.value
    });

    return {
        setAssociates,
        setCompanies,
        setCompany,
        getAssociates,
        getCompanies,
        getCompany
    }

});