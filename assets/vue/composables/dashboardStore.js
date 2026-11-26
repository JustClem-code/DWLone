import { useFetch } from '../composables/fetch.js'
import { useLogic } from '../composables/useLogic.js'

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')
const { data: processDatas, error: errorProcessDatas } = useFetch('/getprocessdatas')
const { data: yardTruckStats, error: errorYardTruckStats } = useFetch('/getyardtruckstats')
const { data: unloadingStats, error: errorUnloadingStats } = useFetch('/getunloadingstats')
const { data: allPackagesStats, error: errorPackagesStats } = useFetch('/getPackagesStats')
const { data: allRoadParts, error: errorGetAllRoadParts } = useFetch('/getAllRoadParts')

const { updateListElement } = useLogic()

export function dashboardStore() {

  const updateProcessData = (data) => {
    updateListElement(processDatas.value.allGroupPostcodes, data.value.groupPostcodes);
    updateListElement(processDatas.value.allAisles, data.value.aisle);
    updateListElement(processDatas.value.allAisles, data.value.previousAisle);
  }

  const updateLocationsData = (data) => {
    data.value.impactedLocations.forEach((impactedLocation) => {
      locations.value.forEach((group) => {
        updateListElement(group, impactedLocation)
      })
    })
  }

  const updateRoadPartsData = (data) => {
    data.value.impactedRoadParts.forEach((impactedRoadPart) => {
      updateListElement(allRoadParts.value, impactedRoadPart)
    })
  }

  const updateDashboardData = (data) => {
    if ('locations' in data.value) {
      locations.value = data.value.locations
    }

    if ('impactedLocations' in data.value) {
      updateLocationsData(data);
      console.log('impactedLocations', data.value.impactedLocations)
    }

    if ('processDatas' in data.value) {
      processDatas.value = data.value.processDatas
    }

    if ('yardTruckStats' in data.value) {
      yardTruckStats.value = data.value.yardTruckStats
    }

    if ('unloadingStats' in data.value) {
      unloadingStats.value = data.value.unloadingStats
    }

    if ('allPackagesStats' in data.value) {
      allPackagesStats.value = data.value.allPackagesStats
    }

    if ('allRoadParts' in data.value) {
      allRoadParts.value = data.value.allRoadParts
    }

    if ('impactedRoadParts' in data.value) {
      updateRoadPartsData(data);
      console.log('impactedRoadParts', data.value.impactedRoadParts)
    }
  }

  return {
    locations, errorLocations,
    processDatas, errorProcessDatas,
    yardTruckStats, errorYardTruckStats,
    unloadingStats, errorUnloadingStats,
    allPackagesStats, errorPackagesStats,
    allRoadParts, errorGetAllRoadParts,
    updateDashboardData,
    updateProcessData
  }
}
