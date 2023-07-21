import React from 'react'

const ContainerModal = (props) => {
  return (
    <div onClick={props.onClose} style={{position:"absolute", top:0, right:0, left:0, bottom:0, background:"rgba(0,0,0,.8)"}}>
      {props.children}
    </div>
  )
}

export default ContainerModal