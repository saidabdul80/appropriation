
export async function  useMonthYearTrigger(selectedFundCategory, selectedSchemeId) {
        if (selectedSchemeId) {
            let res = await postData('/get_prepared_data', {
                scheme_id: selectedSchemeId,
                fund_category: selectedFundCategory
            }, true);
            if (res?.status == 200) {
                return {
                    appropriations_history: res.data.appropriations_histories,
                    appropriations: res.data.appropriations,
                    selected_month: selectedFundCategory
                };
            } else {
                showAlert('Something went wrong');
                return false;
            }
        } else {
            return selectedFundCategory;
        }
}
