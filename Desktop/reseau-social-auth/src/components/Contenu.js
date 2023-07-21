import React from 'react'
import Card from 'react-bootstrap/Card';

const Contenu = (props) => {
  return (
    <Card style={{ width: '18rem' }}>
      <Card.Body>
        <Card.Text>{props.data.name}</Card.Text>
        <Card.Text>{props.data.prenom}</Card.Text>
        <Card.Text>{props.data.age}</Card.Text>
      </Card.Body>
    </Card>
  )
}

export default Contenu