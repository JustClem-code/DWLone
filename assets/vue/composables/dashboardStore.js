import { useFetch } from '../composables/fetch.js'
import { useLogic } from '../composables/useLogic.js'

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')
const { data: processDatas, error: errorProcessDatas } = useFetch('/getprocessdatas')
const { data: yardTruckStats, error: errorYardTruckStats } = useFetch('/getyardtruckstats')
const { data: allPackagesStats, error: errorPackagesStats } = useFetch('/getPackagesStats')
const { data: allRoadParts, error: errorGetAllRoadParts } = useFetch('/getAllRoadParts')

const { updateListElement } = useLogic()

export function dashboardStore() {

  const updateProcessData = (data) => {
    console.log('Updating list element:', processDatas.value.allGroupPostcodes);
    updateListElement(processDatas.value.allGroupPostcodes, data.value.groupPostcodes);
    updateListElement(processDatas.value.allAisles, data.value.aisle);
    updateListElement(processDatas.value.allAisles, data.value.previousAisle);
  }

  const updateDashboardData = (data) => {
    if ('locations' in data.value) {
      locations.value = data.value.locations
    }

    if ('processDatas' in data.value) {
      processDatas.value = data.value.processDatas
    }

    if ('yardTruckStats' in data.value) {
      yardTruckStats.value = data.value.yardTruckStats
    }

    if ('allPackagesStats' in data.value) {
      allPackagesStats.value = data.value.allPackagesStats
    }

    if ('allRoadParts' in data.value) {
      allRoadParts.value = data.value.allRoadParts
    }
  }

  return {
    locations, errorLocations,
    processDatas, errorProcessDatas,
    yardTruckStats, errorYardTruckStats,
    allPackagesStats, errorPackagesStats,
    allRoadParts, errorGetAllRoadParts,
    updateDashboardData,
    updateProcessData
  }
}
