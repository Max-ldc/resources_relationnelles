import React  from 'react';
import './search-css.css';
import {Button, FieldError, Input, SearchField} from 'react-aria-components';

function Search() {

  // let { contains } = useFilter({ sensitivity: 'base' });
  let [value, setValue] = React.useState('');
  // let matchedComposers = composers.filter((composer) =>
  //   contains(composer, value)
  // );

  
    return (
      <div>
           <main>
              <section className='search-contanair'>

              <SearchField label="Effectuez une recherche" onClear={() => setValue('')} >
              <Input  placeholder='Effectuez une recherche' type='search' onChange={(e) => setValue(e.target.value)}
                        value={value}  className='input-champ'/>
                <Button  className='button' onSubmit={setValue}> Chercher </Button>
                <FieldError />
              </SearchField>

                {/* <p style={{ height: 300 }}>
                  {matchedComposers.map((composer, i) => <li key={i}>{composer}</li>)}
                </p>             */}
                          </section>
            </main>
      </div>
    );
}
   
export default Search;









