<template>
    <div class="position-relative" style="min-width: 320px;max-width: 500px;">
        <a @click="isPopupVisible = !isPopupVisible" class="d-flex align-items-center mx-2" style="cursor: pointer;">{{ label }} <span
                class="pi pi-angle-down mx-1"></span>
        </a>
        <div class="popup-container" v-if="isPopupVisible" @click="togglePopup">
            <span class="pi pi-times pull-right bg-danger p-1 rounded" style="cursor: pointer;" @click="isPopupVisible=false"></span>
            <div class="popup-content" @click.stop>
                <div class="scrollable-container" @click="visibility = false"
                    style="height: 260px; overflow-y: auto;cursor: grab;user-select: none;" @mousedown="startDragging"
                    @mouseup="stopDragging" @mouseleave="stopDragging" @mousemove="scrollOnDrag">
                    <div class="scrollable-content" ref="scrollContent" style="min-width: 300px; width: 400px;">
                        <div style="position: relative;width:inherit">
                            <DataTable @row-collapse="visibility = false" v-model:expandedRows="expandedRows" :value="items"
                                dataKey="id">

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
                                        <DataTable :value="slotProps.data.children">
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
            </div>
        </div>

    </div>
</template>

<script>

import { useConfirm } from "primevue/useconfirm";

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import ConfirmPopup from 'primevue/confirmpopup';
export default {
    components: {
        ColumnGroup,
        Column,
        Row,
        DataTable,
        'v-popup': ConfirmPopup
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
