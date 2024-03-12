import React from 'react';
import {SearchField} from '@adobe/react-spectrum'
import './search-css.css';


function Search() {
    let [currentText, setCurrentText] = React.useState('');
    let [submittedText, setSubmittedText] = React.useState('');

    return (
      <div>
           <main>
              <section className='search-contanair'>


                      <SearchField
                      label="Search" width="size-3600"
                            onClear={() => setCurrentText('')}
                            onChange={setCurrentText}
                            onSubmit={setSubmittedText}
                            value={currentText}
                        />
                        <pre>Submitted text: {submittedText}</pre>

                    
             
            
                          </section>
            </main>
      </div>
    );
  }
  
  
export default Search;









