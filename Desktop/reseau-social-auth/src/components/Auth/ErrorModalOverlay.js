import React from 'react'
import ReactDOM from 'react-dom'
import { Alert } from 'react-bootstrap'
import ContainerModal from './ContainerModal'
import ButtonSubmit from '../UI/ButtonSubmit'

const ErrorModalOverlay = (props) => {
  return (
    <>
      {
        ReactDOM.createPortal(
          <ContainerModal onClose={props.close}>
          <div style={{position:"absolute", top:"50%", left:"50%", transform:"translate(-50%, -50%)"}}>
            <Alert variant="danger" onClose={props.close} dismissible>
              <Alert.Heading>{props.value}</Alert.Heading>
            </Alert>
          </div>
        </ContainerModal>, 
        document.querySelector('#modal-root'))
      }
    </>
  )
}

export default ErrorModalOverlay