import { useFetch } from '../composables/fetch.js'

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')
const { data: allPackagesStats, error: errorPackagesStats } = useFetch('/getPackagesStats')
const { data: yardTruckStats, error: errorYardTruckStats } = useFetch('/getyardtruckstats')
const { data: allRoadParts, error: errorGetAllRoadParts } = useFetch('/getAllRoadParts')

export function dashboardStore() {

  const updateDashboardData = (data) => {
    if ('locations' in data.value) {
      locations.value = data.value.locations
    }

    if ('allPackagesStats' in data.value) {
      allPackagesStats.value = data.value.allPackagesStats
    }

    if ('yardTruckStats' in data.value) {
      yardTruckStats.value = data.value.yardTruckStats
    }

    if ('allRoadParts' in data.value) {
      allRoadParts.value = data.value.allRoadParts
    }
  }

  return { locations, allPackagesStats, yardTruckStats, allRoadParts, errorGetAllRoadParts, updateDashboardData }
}
