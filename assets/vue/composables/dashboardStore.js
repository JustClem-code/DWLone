import { useFetch } from '../composables/fetch.js'

const { data: locations, error: errorLocations } = useFetch('/getBagsInLocations')
const { data: allPackagesStats, error: errorPackagesStats } = useFetch('/getPackagesStats')
const { data: yardTruckStats, error: errorYardTruckStats } = useFetch('/getyardtruckstats')

export function dashboardStore() {

  const updateDashboardData = (data) => {
    locations.value = data.value.locations
    allPackagesStats.value = data.value.allPackagesStats
    yardTruckStats.value = data.value.yardTruckStats
  }

  return { locations, allPackagesStats, yardTruckStats, updateDashboardData }
}
