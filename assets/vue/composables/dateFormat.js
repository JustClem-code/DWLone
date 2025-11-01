export function formattedDateFr(d) {
  if (!d) {
    return
  }
  const date = new Date(d.date);
  return date.toLocaleString('fr-FR');
  // const date = new Date(d.date.replace(' ', 'T') + 'Z');
  // return date.toLocaleString('fr-FR', {
  //   year: 'numeric',
  //   month: 'long',
  //   day: '2-digit',
  //   hour: '2-digit',
  //   minute: '2-digit'
  // });
}
