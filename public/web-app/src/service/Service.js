import baseDonne from './bd.json'

//Как создат функции(свободные,без html) для применения на любой страницу
export  const getAll =()=>{ return baseDonne }
export const getOne =(id)=>{
    return baseDonne.find(logement=>logement.id===id)
}
//или так
// export {getAll,getOne}