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

  const formatInt = (n) => {
    const value = n / 1000;
    return new Intl.NumberFormat('fr-FR', {
      minimumFractionDigits: 3,
      maximumFractionDigits: 3
    }).format(value);
  }

  return { getNumberOfPackagesNotInducted, getPalletsNotUnloaded, handleMenuAction, formatInt };
}
