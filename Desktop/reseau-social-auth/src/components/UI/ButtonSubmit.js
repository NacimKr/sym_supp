import React from 'react'
import { Button } from 'react-bootstrap'

const ButtonSubmit = (props) => {
  return (
    <Button variant={props.color} type={props.type || "submit"} onClick={props.click}>
      {props.children}
    </Button>
  )
}

export default ButtonSubmit