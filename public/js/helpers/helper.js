function limit(string="", limit=25){
  if(string.length >= limit){
  return string.substring(0, limit) + '...'
}  
  return string.substring(0, limit)
}