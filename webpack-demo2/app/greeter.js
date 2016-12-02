/**
 * Created by Administrator on 2016/10/14 0014.
 */
import React, {Component} from 'react';
import greetJson from './config.json';
import styles from './greeter.css';

class Greeter extends Component {
	render() {
		return (
			<div className={styles.root}>
			 	{greetJson.greetJson}
			</div>
		)
	}
}

export default Greeter;
