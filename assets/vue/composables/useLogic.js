import { ref, onMounted } from 'vue';

export function useLogic() {

  const getNumberOfPackagesNotInducted = (pallet) => {
    if (!pallet) return
    return pallet?.packages?.filter(p => p.location === null).length;
  }

  const getPalletsNotUnloaded = (pallets) => {
    if (!pallets) return
    return pallets?.filter(pallet => pallet.userId === null);
  }

  const handleMenuAction = (actionKey, actionsMap,
  ) => {
    const fn = actionsMap[actionKey]
    if (fn) fn()
  }

  return { getNumberOfPackagesNotInducted, getPalletsNotUnloaded, handleMenuAction };
}
