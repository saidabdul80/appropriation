<template>
    <div class="position-relative" style="min-width: 320px;max-width: 500px;">
        <a @click="isPopupVisible = !isPopupVisible" class="d-flex align-items-center mx-2" style="cursor: pointer;">{{ label }} <span
                class="pi pi-angle-down mx-1"></span>
        </a>
        <Dialog v-model:visible="isPopupVisible" modal header="More Details" :style="{ width: '50rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <div class="scrollable-container" >
                <div class="scrollable-content" ref="scrollContent">
                    <div style="position: relative;width:inherit">
                        <DataTable  v-model:expandedRows="expandedRows" :value="items"
                            dataKey="id" ref="dt">
                            <template #header>
                                <div style="text-align: left">
                                    <button class="btn btn-success pi pi-external-link"  @click="exportCSV('dt')">  Export</button>
                                </div>
                            </template>
                            <Column expander style="width: 5rem" :pt="{root:{style:{padding:'0px !important'}}}" />
                            <Column field="name" header="Subhead"></Column>
                            <Column field="amount" header="Budget">
                                <template #body="slotProps">
                                    {{ $globals.currency(slotProps?.data?.amount) }}
                                </template>
                            </Column>
                            <Column field="balance" header="Balance">
                                <template #body="slotProps">
                                    {{ $globals.currency(slotProps?.data?.balance) }}
                                </template>
                            </Column>
                            <template #expansion="slotProps">
                                <div class="p-3">
                                    <h6 class="text-center">Activity Budget</h6>
                                    <DataTable :value="slotProps.data.children" :ref="slotProps.data?.name.replaceAll(' ','_')">
                                        <template #header>
                                            <div style="text-align: left">
                                                <button class="btn btn-success pi pi-external-link"  @click="exportCSV(slotProps.data?.name.replaceAll(' ','_'))"></button>
                                            </div>
                                        </template>
                                        <Column field="" header="#">
                                            <template #body="{slotProps, frozenRow, index  }">
                                                {{ index +1 }}
                                            </template>
                                        </Column>
                                        <Column field="name" header="Activity"></Column>
                                        <Column field="amount" header="Budget">
                                            <template #body="slotProps">
                                                {{ $globals.currency(slotProps?.data?.amount) }}
                                            </template>
                                        </Column>
                                        <Column field="balance" header="Balance">
                                            <template #body="slotProps">
                                                {{ $globals.currency(slotProps?.data?.balance) }}
                                            </template>
                                        </Column>
                                    </DataTable>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </Dialog>


    </div>
</template>

<script>

import { useConfirm } from "primevue/useconfirm";

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Dialog from 'primevue/dialog';
export default {
    components: {
        ColumnGroup,
        Column,
        Row,
        DataTable,
        Dialog
    },
    props: [
        'headers',
        'items',
        'subitem',
        'label'
    ],
    data() {
        return {
            isPopupVisible: false,
            showPopup: false,
            visibility: true,
            isDragging: false,
            startY: 0,
            scrollTop: 0,
            confirm: useConfirm(),
        }
    },
    methods: {
        startDragging(event) {
            this.isDragging = true;
            this.startY = event.clientY;
            this.scrollTop = this.$refs.scrollContent?.scrollTop;
        },
        stopDragging() {
            this.isDragging = false;
        },
        scrollOnDrag(event) {
            if (this.isDragging) {
                const deltaY = event.clientY - this.startY;
                try {
                    this.$refs.scrollContent.scrollTop = this.scrollTop - deltaY;

                } catch (e) {

                }
            }
        },
        showPopupModal(event) {
            this.showPopup = true;
            this.confirm.require({
                target: event.currentTarget
            });
        },
        download(mainTableData){
            // Combine and format data for CSV
                let csvContent =""

                // Iterate through the main table data and subtable data
                mainTableData.forEach((row, index) => {
                    // Process only non-empty rows
                    if (Object.keys(row).length > 0) {
                        // Convert each row to CSV format
                        const rowValues = Object.values(row).map(value => `"${value}"`).join(",");
                        csvContent += `${rowValues}\n`;
                    } else {
                        // Insert an empty line for separation between main table and subtable
                        csvContent += "\n";
                    }
                });

                // Create a Blob object with the CSV data
                const blob = new Blob([csvContent], { type: "text/csv" });

                // Create a temporary anchor element to trigger the download
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.setAttribute("download", "table_data.csv");

                // Programmatically trigger the download
                document.body.appendChild(link);
                link.click();

                // Cleanup
                document.body.removeChild(link);

        },
        exportCSV(dt) {
            // Create an empty array to store all table data

            const allTableData  =[]
            // Iterate through the expansion rows
            this.items.forEach((row, index) => {
                // Get the DataTable reference for each expanded row
                allTableData.push({0:''}); //new line for separation
                allTableData.push(row); //new line for separation
                allTableData.push({0:''}); //new line for separation
                if(row.children.length>0){

                    allTableData.splice(index, 0, ...row.children);
                }
                allTableData.push({0:''}); //new line for separation
            });

           this.download(allTableData)
        }

    }


}
</script>
<style scoped>
a {
    text-decoration: none;
}

.popup-container {
    position: fixed;
   /*  top: 180px;
    left: 0;
    width: 100%; */
    height: 100%;
    z-index: 9999;
    /* Ensure popup is on top */
  /*   display: flex; */
    justify-content: center;
    align-items: center;
}

.popup-content {

    /* Adjust width as needed */
    padding: 20px;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
</style>
